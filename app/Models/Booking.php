<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'flight_id',
        'booking_reference',
        'passengers',
        'total_price',
        'currency',
        'status',
        'contact_email',
        'itinerary_snapshot',
        'last_price_notified',
        'last_status_notified',
        'last_notified_at',
    ];

    protected $casts = [
        'itinerary_snapshot' => 'array',
        'total_price' => 'decimal:2',
        'last_price_notified' => 'decimal:2',
        'last_notified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }
}
