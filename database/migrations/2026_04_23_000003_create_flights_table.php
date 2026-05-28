<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->string('airline');
            $table->string('flight_number');
            $table->string('cabin_class')->default('Economy');
            $table->string('departure_city');
            $table->string('departure_country');
            $table->string('departure_airport');
            $table->string('departure_airport_code', 8);
            $table->string('departure_timezone')->default('UTC');
            $table->dateTime('departure_at');
            $table->string('arrival_city');
            $table->string('arrival_country');
            $table->string('arrival_airport');
            $table->string('arrival_airport_code', 8);
            $table->string('arrival_timezone')->default('UTC');
            $table->dateTime('arrival_at');
            $table->json('stops')->nullable();
            $table->unsignedTinyInteger('seats_available')->default(24);
            $table->unsignedTinyInteger('demand_index')->default(50);
            $table->decimal('base_price', 10, 2);
            $table->decimal('current_price', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('confirmed');
            $table->timestamp('price_last_updated_at')->nullable();
            $table->text('baggage_info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
