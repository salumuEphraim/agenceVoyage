<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Flight;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlightChangeNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public Flight $flight,
        public array $changes
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'CMKS Travel - Mise a jour de votre vol ' . $this->booking->booking_reference,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.flight-change',
        );
    }
}
