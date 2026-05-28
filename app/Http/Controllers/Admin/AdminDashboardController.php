<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Destination;
use App\Models\Flight;

class AdminDashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.dashboard', [
            'destinations' => Destination::orderByDesc('is_featured')->orderBy('city')->get(),
            'flights' => Flight::with(['destination', 'bookings'])->orderBy('departure_at')->get(),
            'bookings' => Booking::with(['user', 'flight.destination'])->latest()->get(),
            'stats' => [
                'destinations' => Destination::count(),
                'flights' => Flight::count(),
                'bookings' => Booking::count(),
                'revenue' => Booking::sum('total_price'),
            ],
        ]);
    }
}
