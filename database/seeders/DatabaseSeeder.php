<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Destination;
use App\Models\Flight;
use App\Models\User;
use App\Services\FlightPricingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@cmkstravel.test'],
            [
                'name' => 'CMKS Admin',
                'is_admin' => true,
                'password' => Hash::make('password'),
            ]
        );

        $client = User::updateOrCreate(
            ['email' => 'client@cmkstravel.test'],
            [
                'name' => 'Grace Mulamba',
                'is_admin' => false,
                'password' => Hash::make('password'),
            ]
        );

        $destinations = collect([
            [
                'city' => 'Lubumbashi',
                'country' => 'RDC',
                'continent' => 'Afrique',
                'airport_name' => 'Luano Airport',
                'airport_code' => 'FBM',
                'timezone' => 'Africa/Lubumbashi',
                'latitude' => -11.6876020,
                'longitude' => 27.5026174,
                'hero_image' => 'images/destinations/lubumbashi.jpg',
                'description' => 'Hub regional CMKS Travel pour les departs premium, affaires et leisure.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'city' => 'Paris',
                'country' => 'France',
                'continent' => 'Europe',
                'airport_name' => 'Charles de Gaulle',
                'airport_code' => 'CDG',
                'timezone' => 'Europe/Paris',
                'latitude' => 48.8566130,
                'longitude' => 2.3522220,
                'hero_image' => 'images/destinations/Beautiful.jpg',
                'description' => 'Capitale elegante, escapades premium, culture et experiences signature.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'city' => 'Marrakech',
                'country' => 'Maroc',
                'continent' => 'Afrique',
                'airport_name' => 'Marrakech-Menara',
                'airport_code' => 'RAK',
                'timezone' => 'Africa/Casablanca',
                'latitude' => 31.6294723,
                'longitude' => -7.9810845,
                'hero_image' => 'images/destinations/maroc1.jpg',
                'description' => 'Riads, gastronomie et sejours haut de gamme dans la ville ocre.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'city' => 'Windhoek',
                'country' => 'Namibie',
                'continent' => 'Afrique',
                'airport_name' => 'Hosea Kutako International',
                'airport_code' => 'WDH',
                'timezone' => 'Africa/Windhoek',
                'latitude' => -22.5608800,
                'longitude' => 17.0657550,
                'hero_image' => 'images/destinations/Namibia.jpg',
                'description' => 'Safari, desert et circuits exclusifs vers Sossusvlei et Swakopmund.',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'city' => 'Zanzibar',
                'country' => 'Tanzanie',
                'continent' => 'Afrique',
                'airport_name' => 'Abeid Amani Karume International',
                'airport_code' => 'ZNZ',
                'timezone' => 'Africa/Dar_es_Salaam',
                'latitude' => -6.1659170,
                'longitude' => 39.2026410,
                'hero_image' => 'images/destinations/vacance2.jpg',
                'description' => 'Lagon turquoise, resorts et programmes balneaires sur mesure.',
                'is_featured' => false,
                'is_active' => true,
            ],
        ])->map(fn (array $destination) => Destination::updateOrCreate(
            ['airport_code' => $destination['airport_code']],
            $destination
        ));

        $flightsData = [
            [
                'destination_id' => $destinations->firstWhere('airport_code', 'CDG')->id,
                'airline' => 'Air France',
                'flight_number' => 'AF452',
                'cabin_class' => 'Economy',
                'departure_city' => 'Lubumbashi',
                'departure_country' => 'RDC',
                'departure_airport' => 'Luano Airport',
                'departure_airport_code' => 'FBM',
                'departure_timezone' => 'Africa/Lubumbashi',
                'departure_at' => '2026-05-02 09:15:00',
                'arrival_city' => 'Paris',
                'arrival_country' => 'France',
                'arrival_airport' => 'Charles de Gaulle',
                'arrival_airport_code' => 'CDG',
                'arrival_timezone' => 'Europe/Paris',
                'arrival_at' => '2026-05-02 18:55:00',
                'stops' => ['Addis-Abeba'],
                'seats_available' => 14,
                'demand_index' => 72,
                'base_price' => 840,
                'current_price' => 840,
                'currency' => 'EUR',
                'status' => 'confirmed',
                'baggage_info' => '23kg cabine incluse + 23kg soute.',
            ],
            [
                'destination_id' => $destinations->firstWhere('airport_code', 'RAK')->id,
                'airline' => 'Royal Air Maroc',
                'flight_number' => 'AT287',
                'cabin_class' => 'Business',
                'departure_city' => 'Lubumbashi',
                'departure_country' => 'RDC',
                'departure_airport' => 'Luano Airport',
                'departure_airport_code' => 'FBM',
                'departure_timezone' => 'Africa/Lubumbashi',
                'departure_at' => '2026-05-06 07:10:00',
                'arrival_city' => 'Marrakech',
                'arrival_country' => 'Maroc',
                'arrival_airport' => 'Marrakech-Menara',
                'arrival_airport_code' => 'RAK',
                'arrival_timezone' => 'Africa/Casablanca',
                'arrival_at' => '2026-05-06 14:20:00',
                'stops' => ['Casablanca'],
                'seats_available' => 7,
                'demand_index' => 83,
                'base_price' => 1260,
                'current_price' => 1260,
                'currency' => 'EUR',
                'status' => 'confirmed',
                'baggage_info' => 'Acces salon, priorite et 2 bagages soute.',
            ],
            [
                'destination_id' => $destinations->firstWhere('airport_code', 'WDH')->id,
                'airline' => 'Ethiopian Airlines',
                'flight_number' => 'ET975',
                'cabin_class' => 'Economy',
                'departure_city' => 'Lubumbashi',
                'departure_country' => 'RDC',
                'departure_airport' => 'Luano Airport',
                'departure_airport_code' => 'FBM',
                'departure_timezone' => 'Africa/Lubumbashi',
                'departure_at' => '2026-05-11 11:40:00',
                'arrival_city' => 'Windhoek',
                'arrival_country' => 'Namibie',
                'arrival_airport' => 'Hosea Kutako International',
                'arrival_airport_code' => 'WDH',
                'arrival_timezone' => 'Africa/Windhoek',
                'arrival_at' => '2026-05-11 18:30:00',
                'stops' => ['Johannesburg'],
                'seats_available' => 18,
                'demand_index' => 58,
                'base_price' => 690,
                'current_price' => 690,
                'currency' => 'EUR',
                'status' => 'delayed',
                'baggage_info' => '1 bagage cabine et 1 bagage soute 23kg.',
            ],
            [
                'destination_id' => $destinations->firstWhere('airport_code', 'ZNZ')->id,
                'airline' => 'Kenya Airways',
                'flight_number' => 'KQ621',
                'cabin_class' => 'Economy',
                'departure_city' => 'Lubumbashi',
                'departure_country' => 'RDC',
                'departure_airport' => 'Luano Airport',
                'departure_airport_code' => 'FBM',
                'departure_timezone' => 'Africa/Lubumbashi',
                'departure_at' => '2026-05-14 08:00:00',
                'arrival_city' => 'Zanzibar',
                'arrival_country' => 'Tanzanie',
                'arrival_airport' => 'Abeid Amani Karume International',
                'arrival_airport_code' => 'ZNZ',
                'arrival_timezone' => 'Africa/Dar_es_Salaam',
                'arrival_at' => '2026-05-14 14:05:00',
                'stops' => ['Nairobi'],
                'seats_available' => 21,
                'demand_index' => 64,
                'base_price' => 520,
                'current_price' => 520,
                'currency' => 'EUR',
                'status' => 'confirmed',
                'baggage_info' => 'Selection de siege standard incluse.',
            ],
        ];

        $flights = collect($flightsData)->map(fn (array $flight) => Flight::updateOrCreate(
            ['flight_number' => $flight['flight_number']],
            $flight
        ));

        $pricingService = app(FlightPricingService::class);
        $pricingService->syncCollection($flights, 'database seed');

        $bookedFlight = $flights->firstWhere('flight_number', 'AF452');

        if ($bookedFlight) {
            Booking::updateOrCreate(
                ['booking_reference' => 'CMKS2026'],
                [
                    'user_id' => $client->id,
                    'flight_id' => $bookedFlight->id,
                    'passengers' => 2,
                    'total_price' => ((float) $bookedFlight->current_price) * 2,
                    'currency' => 'EUR',
                    'status' => $bookedFlight->status,
                    'contact_email' => $client->email,
                    'itinerary_snapshot' => [
                        'route' => $bookedFlight->route_label,
                        'departure_airport' => $bookedFlight->departure_airport,
                        'arrival_airport' => $bookedFlight->arrival_airport,
                        'stops' => $bookedFlight->stops,
                        'duration_label' => $bookedFlight->duration_label,
                    ],
                    'last_price_notified' => $bookedFlight->current_price,
                    'last_status_notified' => $bookedFlight->status,
                    'last_notified_at' => now(),
                ]
            );
        }
    }
}
