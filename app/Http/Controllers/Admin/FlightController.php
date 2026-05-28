<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\Flight;
use App\Services\FlightNotificationService;
use App\Services\FlightPricingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function __construct(
        protected FlightPricingService $pricingService,
        protected FlightNotificationService $notificationService
    ) {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFlight($request);
        $destination = Destination::findOrFail($validated['destination_id']);
        $validated['arrival_city'] = $destination->city;
        $validated['arrival_country'] = $destination->country;
        $validated['arrival_airport'] = $destination->airport_name;
        $validated['arrival_airport_code'] = $destination->airport_code;
        $validated['arrival_timezone'] = $destination->timezone;
        $validated['current_price'] = $validated['base_price'];
        $validated['stops'] = $this->parseStops($request->input('stops'));

        $flight = Flight::create($validated);
        $changes = $this->pricingService->syncFlight($flight, 'admin create');
        $this->notificationService->notifyAffectedBookings($flight, $changes);

        return back()->with('success', "Vol {$flight->flight_number} ajoute.");
    }

    public function update(Request $request, Flight $flight): RedirectResponse
    {
        $validated = $this->validateFlight($request);
        $destination = Destination::findOrFail($validated['destination_id']);
        $validated['arrival_city'] = $destination->city;
        $validated['arrival_country'] = $destination->country;
        $validated['arrival_airport'] = $destination->airport_name;
        $validated['arrival_airport_code'] = $destination->airport_code;
        $validated['arrival_timezone'] = $destination->timezone;
        $validated['stops'] = $this->parseStops($request->input('stops'));

        $flight->fill($validated);
        $changes = [];

        foreach ($flight->getDirty() as $field => $newValue) {
            $changes[$field] = [
                'old' => $flight->getOriginal($field),
                'new' => $newValue,
            ];
        }

        if (isset($changes['base_price'])) {
            $flight->current_price = $validated['base_price'];
        }

        $flight->save();

        $pricingChanges = $this->pricingService->syncFlight($flight, 'admin update');
        $changes = array_merge($changes, $pricingChanges);
        $notified = $this->notificationService->notifyAffectedBookings($flight, $changes);

        return back()->with('success', "Vol {$flight->flight_number} mis a jour. {$notified} client(s) notifie(s).");
    }

    public function refreshPricing(): RedirectResponse
    {
        $flights = Flight::with('bookings.user')->get();
        $updated = 0;
        $notified = 0;

        foreach ($flights as $flight) {
            $changes = $this->pricingService->syncFlight($flight, 'admin bulk refresh');

            if ($changes === []) {
                continue;
            }

            $updated++;
            $notified += $this->notificationService->notifyAffectedBookings($flight, $changes);
        }

        return back()->with('success', "{$updated} vol(s) revalorise(s), {$notified} notification(s) email envoye(es).");
    }

    protected function validateFlight(Request $request): array
    {
        return $request->validate([
            'destination_id' => ['required', 'exists:destinations,id'],
            'airline' => ['required', 'string', 'max:255'],
            'flight_number' => ['required', 'string', 'max:255'],
            'cabin_class' => ['required', 'string', 'max:255'],
            'departure_city' => ['required', 'string', 'max:255'],
            'departure_country' => ['required', 'string', 'max:255'],
            'departure_airport' => ['required', 'string', 'max:255'],
            'departure_airport_code' => ['required', 'string', 'max:8'],
            'departure_timezone' => ['required', 'string', 'max:120'],
            'departure_at' => ['required', 'date'],
            'arrival_at' => ['required', 'date', 'after:departure_at'],
            'seats_available' => ['required', 'integer', 'min:1', 'max:240'],
            'demand_index' => ['required', 'integer', 'min:10', 'max:100'],
            'base_price' => ['required', 'numeric', 'min:50'],
            'currency' => ['required', 'string', 'size:3'],
            'status' => ['required', 'in:confirmed,delayed,cancelled'],
            'baggage_info' => ['nullable', 'string'],
        ]);
    }

    protected function parseStops(?string $stops): array
    {
        if (! $stops) {
            return [];
        }

        return collect(explode(',', $stops))
            ->map(fn ($stop) => trim($stop))
            ->filter()
            ->values()
            ->all();
    }
}
