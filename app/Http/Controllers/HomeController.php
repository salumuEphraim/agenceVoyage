<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\Flight;
use App\Services\FlightPricingService;

class HomeController extends Controller
{
    public function __construct(protected FlightPricingService $pricingService)
    {
    }

    public function __invoke()
    {
        $featuredFlights = Flight::with('destination')
            ->orderBy('departure_at')
            ->take(3)
            ->get();

        $this->pricingService->syncCollection($featuredFlights, 'homepage refresh');

        return view('accueil', [
            'destinations' => Destination::query()
                ->withMin('flights', 'current_price')
                ->where('is_active', true)
                ->orderByDesc('is_featured')
                ->latest()
                ->get(),
            'featuredFlights' => $featuredFlights,
        ]);
    }
}
