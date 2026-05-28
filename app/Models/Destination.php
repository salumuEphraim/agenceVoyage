<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'city',
        'country',
        'continent',
        'airport_name',
        'airport_code',
        'timezone',
        'latitude',
        'longitude',
        'hero_image',
        'description',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function flights(): HasMany
    {
        return $this->hasMany(Flight::class);
    }

    public function getDisplayNameAttribute(): string
    {
        return "{$this->city}, {$this->country}";
    }
}
