<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id',
        'airline',
        'flight_number',
        'cabin_class',
        'departure_city',
        'departure_country',
        'departure_airport',
        'departure_airport_code',
        'departure_timezone',
        'departure_at',
        'arrival_city',
        'arrival_country',
        'arrival_airport',
        'arrival_airport_code',
        'arrival_timezone',
        'arrival_at',
        'stops',
        'seats_available',
        'demand_index',
        'base_price',
        'current_price',
        'currency',
        'status',
        'price_last_updated_at',
        'baggage_info',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
        'price_last_updated_at' => 'datetime',
        'stops' => 'array',
        'base_price' => 'decimal:2',
        'current_price' => 'decimal:2',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function priceHistory(): HasMany
    {
        return $this->hasMany(FlightPriceHistory::class);
    }

    protected function durationMinutes(): Attribute
    {
        return Attribute::get(function (): int {
            $departure = Carbon::parse($this->departure_at, $this->departure_timezone)->utc();
            $arrival = Carbon::parse($this->arrival_at, $this->arrival_timezone)->utc();

            return max($arrival->diffInMinutes($departure), 0);
        });
    }

    protected function durationLabel(): Attribute
    {
        return Attribute::get(function (): string {
            $minutes = $this->duration_minutes;
            $hours = intdiv($minutes, 60);
            $remaining = $minutes % 60;

            return sprintf('%dh %02d', $hours, $remaining);
        });
    }

    protected function routeLabel(): Attribute
    {
        return Attribute::get(fn (): string => "{$this->departure_city} -> {$this->arrival_city}");
    }

    protected function stopsCount(): Attribute
    {
        return Attribute::get(fn (): int => count($this->stops ?? []));
    }

    protected function formattedPrice(): Attribute
    {
        return Attribute::get(fn (): string => number_format((float) $this->current_price, 0, ',', ' ') . ' ' . $this->currency);
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::get(fn (): string => match ($this->status) {
            'delayed' => 'Retarde',
            'cancelled' => 'Annule',
            default => 'Confirme',
        });
    }
}
