<?php

namespace App\Services;

use App\Mail\FlightChangeNotificationMail;
use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Support\Facades\Mail;

class FlightNotificationService
{
    public function notifyAffectedBookings(Flight $flight, array $changes): int
    {
        if ($changes === []) {
            return 0;
        }

        $bookings = $flight->bookings()->with('user')->get();
        $sent = 0;

        foreach ($bookings as $booking) {
            if (! $this->shouldNotify($booking, $flight, $changes)) {
                continue;
            }

            Mail::to($booking->contact_email)->send(
                new FlightChangeNotificationMail($booking, $flight, $changes)
            );

            if (isset($changes['current_price'])) {
                $booking->last_price_notified = $flight->current_price;
            }

            if (isset($changes['status'])) {
                $booking->last_status_notified = $flight->status;
            }

            $booking->last_notified_at = now();
            $booking->save();
            $sent++;
        }

        return $sent;
    }

    protected function shouldNotify(Booking $booking, Flight $flight, array $changes): bool
    {
        if (isset($changes['current_price']) && (float) $booking->last_price_notified !== (float) $flight->current_price) {
            return true;
        }

        if (isset($changes['status']) && $booking->last_status_notified !== $flight->status) {
            return true;
        }

        return isset($changes['departure_at']) || isset($changes['arrival_at']) || isset($changes['stops']);
    }
}
