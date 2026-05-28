<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Services\FlightNotificationService;
use App\Services\FlightPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function __construct(
        protected FlightPricingService $pricingService,
        protected FlightNotificationService $notificationService
    ) {
    }

    public function store(Request $request, Flight $flight): RedirectResponse
    {
        $validated = $request->validate([
            'passengers' => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        $changes = $this->pricingService->syncFlight($flight, 'booking refresh');
        $this->notificationService->notifyAffectedBookings($flight, $changes);

        $booking = $request->user()->bookings()->create([
            'flight_id' => $flight->id,
            'booking_reference' => strtoupper(Str::random(8)),
            'passengers' => $validated['passengers'],
            'total_price' => ((float) $flight->current_price) * $validated['passengers'],
            'currency' => $flight->currency,
            'status' => $flight->status,
            'contact_email' => $request->user()->email,
            'itinerary_snapshot' => [
                'route' => $flight->route_label,
                'departure_airport' => $flight->departure_airport,
                'arrival_airport' => $flight->arrival_airport,
                'stops' => $flight->stops,
                'duration_label' => $flight->duration_label,
            ],
            'last_price_notified' => $flight->current_price,
            'last_status_notified' => $flight->status,
            'last_notified_at' => now(),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', "Reservation {$booking->booking_reference} confirmee pour {$flight->route_label}.");
    }
}
