<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('flight_id')->constrained()->cascadeOnDelete();
            $table->string('booking_reference')->unique();
            $table->unsignedTinyInteger('passengers')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('status')->default('confirmed');
            $table->string('contact_email');
            $table->json('itinerary_snapshot')->nullable();
            $table->decimal('last_price_notified', 10, 2)->nullable();
            $table->string('last_status_notified')->nullable();
            $table->timestamp('last_notified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
