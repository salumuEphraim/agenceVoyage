<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Flight;
use App\Services\FlightPricingService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected FlightPricingService $pricingService)
    {
    }

    public function __invoke(Request $request)
    {
        $query = Flight::with(['destination', 'bookings'])
            ->where('departure_at', '>=', now()->startOfDay())
            ->orderBy('departure_at');

        if ($request->filled('search')) {
            $term = $request->string('search');
            $query->where(function ($builder) use ($term) {
                $builder
                    ->where('arrival_city', 'like', "%{$term}%")
                    ->orWhere('arrival_country', 'like', "%{$term}%")
                    ->orWhere('departure_city', 'like', "%{$term}%")
                    ->orWhere('airline', 'like', "%{$term}%");
            });
        }

        if ($request->filled('departure_date')) {
            $query->whereDate('departure_at', $request->string('departure_date'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $flights = $query->get();
        $this->pricingService->syncCollection($flights, 'dashboard refresh');

        $user = $request->user();
        $bookings = $user->bookings()
            ->with('flight.destination')
            ->latest()
            ->get();

        return view('dashboard', [
            'flights' => $flights,
            'bookings' => $bookings,
            'destinations' => Destination::where('is_active', true)->orderBy('city')->get(),
            'filters' => $request->only(['search', 'departure_date', 'status']),
            'stats' => [
                'activeBookings' => $bookings->where('status', 'confirmed')->count(),
                'totalSpend' => $bookings->sum('total_price'),
                'nextFlight' => optional($bookings->first()?->flight)->departure_at,
            ],
        ]);
    }
}
