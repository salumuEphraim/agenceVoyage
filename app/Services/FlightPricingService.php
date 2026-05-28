<?php

namespace App\Services;

use App\Models\Flight;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class FlightPricingService
{
    public function syncCollection(Collection $flights, string $reason = 'market refresh'): array
    {
        $updated = 0;

        foreach ($flights as $flight) {
            $changes = $this->syncFlight($flight, $reason);

            if ($changes !== []) {
                $updated++;
            }
        }

        return ['updated' => $updated];
    }

    public function syncFlight(Flight $flight, string $reason = 'market refresh'): array
    {
        $previousPrice = (float) $flight->current_price;
        $newPrice = $this->calculateDynamicPrice($flight);

        if (abs($newPrice - $previousPrice) < 0.01) {
            return [];
        }

        $flight->forceFill([
            'current_price' => $newPrice,
            'price_last_updated_at' => now(),
        ])->save();

        $flight->priceHistory()->create([
            'previous_price' => $previousPrice,
            'new_price' => $newPrice,
            'reason' => $reason,
        ]);

        return [
            'current_price' => [
                'old' => $previousPrice,
                'new' => $newPrice,
                'reason' => $reason,
            ],
        ];
    }

    public function calculateDynamicPrice(Flight $flight): float
    {
        $base = (float) $flight->base_price;
        $daysUntilDeparture = max(now()->diffInDays(Carbon::parse($flight->departure_at), false), 0);

        $urgencyMultiplier = match (true) {
            $daysUntilDeparture <= 3 => 1.22,
            $daysUntilDeparture <= 7 => 1.15,
            $daysUntilDeparture <= 14 => 1.09,
            $daysUntilDeparture <= 30 => 1.04,
            default => 0.98,
        };

        $scarcityMultiplier = match (true) {
            $flight->seats_available <= 6 => 1.18,
            $flight->seats_available <= 12 => 1.11,
            $flight->seats_available <= 20 => 1.05,
            default => 0.99,
        };

        $demandMultiplier = 0.85 + ($flight->demand_index / 100);
        $weekendMultiplier = Carbon::parse($flight->departure_at)->isWeekend() ? 1.06 : 1.0;

        $pulseSeed = crc32(now()->format('Y-m-d-H') . '-' . $flight->id);
        $pulseMultiplier = 0.97 + (($pulseSeed % 10) / 100);

        $computed = $base * $urgencyMultiplier * $scarcityMultiplier * $demandMultiplier * $weekendMultiplier * $pulseMultiplier;
        $rounded = round($computed / 5) * 5;

        return max($rounded, round($base * 0.82));
    }
}
