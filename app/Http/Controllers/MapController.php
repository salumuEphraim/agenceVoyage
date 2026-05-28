<?php

namespace App\Http\Controllers;

use App\Models\Destination;

class MapController extends Controller
{
    public function __invoke()
    {
        $destinations = Destination::query()
            ->with('flights')
            ->where('is_active', true)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderByDesc('is_featured')
            ->orderBy('city')
            ->get();

        return view('carte', [
            'destinations' => $destinations,
            'continents' => $destinations->pluck('continent')->filter()->unique()->values(),
            'mapDestinations' => $destinations->map(function (Destination $destination) {
                $lowestFlightPrice = $destination->flights->min('current_price');

                return [
                    'id' => $destination->id,
                    'city' => $destination->city,
                    'country' => $destination->country,
                    'continent' => $destination->continent,
                    'airport_code' => $destination->airport_code,
                    'airport_name' => $destination->airport_name,
                    'latitude' => (float) $destination->latitude,
                    'longitude' => (float) $destination->longitude,
                    'price' => $lowestFlightPrice ? (float) $lowestFlightPrice : null,
                    'price_label' => $lowestFlightPrice
                        ? number_format((float) $lowestFlightPrice, 0, ',', ' ') . ' EUR'
                        : 'Sur demande',
                ];
            })->values(),
        ]);
    }
}
