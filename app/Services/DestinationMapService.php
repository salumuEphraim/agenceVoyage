<?php

namespace App\Services;

class DestinationMapService
{
    public function enrich(array $destination): array
    {
        if (! empty($destination['latitude']) && ! empty($destination['longitude'])) {
            return $destination;
        }

        $resolved = $this->resolveCoordinates(
            $destination['city'] ?? '',
            $destination['country'] ?? '',
            $destination['continent'] ?? null,
        );

        if ($resolved === null) {
            return $destination;
        }

        $destination['latitude'] = $destination['latitude'] ?? $resolved['latitude'];
        $destination['longitude'] = $destination['longitude'] ?? $resolved['longitude'];
        $destination['continent'] = $destination['continent'] ?: $resolved['continent'];

        return $destination;
    }

    public function resolveCoordinates(string $city, string $country, ?string $continent = null): ?array
    {
        $key = mb_strtolower(trim($city) . '|' . trim($country));

        $cityCoordinates = [
            'lubumbashi|rdc' => ['latitude' => -11.6876020, 'longitude' => 27.5026174, 'continent' => 'Afrique'],
            'paris|france' => ['latitude' => 48.8566130, 'longitude' => 2.3522220, 'continent' => 'Europe'],
            'marrakech|maroc' => ['latitude' => 31.6294723, 'longitude' => -7.9810845, 'continent' => 'Afrique'],
            'zanzibar|tanzanie' => ['latitude' => -6.1659170, 'longitude' => 39.2026410, 'continent' => 'Afrique'],
            'windhoek|namibie' => ['latitude' => -22.5608800, 'longitude' => 17.0657550, 'continent' => 'Afrique'],
            'dakar|senegal' => ['latitude' => 14.7166770, 'longitude' => -17.4676860, 'continent' => 'Afrique'],
            'casablanca|maroc' => ['latitude' => 33.5731104, 'longitude' => -7.5898434, 'continent' => 'Afrique'],
            'bruxelles|belgique' => ['latitude' => 50.8503396, 'longitude' => 4.3517103, 'continent' => 'Europe'],
            'londres|royaume-uni' => ['latitude' => 51.5072178, 'longitude' => -0.1275862, 'continent' => 'Europe'],
            'new york|usa' => ['latitude' => 40.7127753, 'longitude' => -74.0059728, 'continent' => 'Amerique'],
            'tokyo|japon' => ['latitude' => 35.6768601, 'longitude' => 139.7638947, 'continent' => 'Asie'],
            'dubai|emirats arabes unis' => ['latitude' => 25.2048493, 'longitude' => 55.2707828, 'continent' => 'Asie'],
        ];

        if (isset($cityCoordinates[$key])) {
            return $cityCoordinates[$key];
        }

        $countryCentroids = [
            'rdc' => ['latitude' => -2.8796623, 'longitude' => 23.6560000, 'continent' => 'Afrique'],
            'france' => ['latitude' => 46.2276380, 'longitude' => 2.2137490, 'continent' => 'Europe'],
            'maroc' => ['latitude' => 31.7917020, 'longitude' => -7.0926200, 'continent' => 'Afrique'],
            'tanzanie' => ['latitude' => -6.3690280, 'longitude' => 34.8888220, 'continent' => 'Afrique'],
            'namibie' => ['latitude' => -22.9576400, 'longitude' => 18.4904100, 'continent' => 'Afrique'],
            'senegal' => ['latitude' => 14.4974010, 'longitude' => -14.4523620, 'continent' => 'Afrique'],
            'belgique' => ['latitude' => 50.5038870, 'longitude' => 4.4699360, 'continent' => 'Europe'],
            'royaume-uni' => ['latitude' => 55.3780510, 'longitude' => -3.4359730, 'continent' => 'Europe'],
            'usa' => ['latitude' => 37.0902400, 'longitude' => -95.7128910, 'continent' => 'Amerique'],
            'japon' => ['latitude' => 36.2048240, 'longitude' => 138.2529240, 'continent' => 'Asie'],
        ];

        $countryKey = mb_strtolower(trim($country));

        if (isset($countryCentroids[$countryKey])) {
            return $countryCentroids[$countryKey];
        }

        if ($continent) {
            return match (mb_strtolower(trim($continent))) {
                'afrique' => ['latitude' => 1.6508010, 'longitude' => 17.6790760, 'continent' => 'Afrique'],
                'europe' => ['latitude' => 54.5260000, 'longitude' => 15.2551000, 'continent' => 'Europe'],
                'asie' => ['latitude' => 34.0478630, 'longitude' => 100.6196550, 'continent' => 'Asie'],
                'amerique' => ['latitude' => 8.7831950, 'longitude' => -55.4914770, 'continent' => 'Amerique'],
                default => null,
            };
        }

        return null;
    }
}
