<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'previous_price',
        'new_price',
        'reason',
    ];

    protected $casts = [
        'previous_price' => 'decimal:2',
        'new_price' => 'decimal:2',
    ];

    public function flight(): BelongsTo
    {
        return $this->belongsTo(Flight::class);
    }
}
