<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\FlightController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/carte', MapController::class)->name('carte');

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/flights/{flight}/book', [BookingController::class, 'store'])->name('bookings.store');

    Route::view('/profile', 'profile')->name('profile');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', AdminDashboardController::class)->name('dashboard');
    Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store');
    Route::patch('/destinations/{destination}', [DestinationController::class, 'update'])->name('destinations.update');
    Route::post('/flights/refresh-pricing', [FlightController::class, 'refreshPricing'])->name('flights.refresh-pricing');
    Route::post('/flights', [FlightController::class, 'store'])->name('flights.store');
    Route::patch('/flights/{flight}', [FlightController::class, 'update'])->name('flights.update');
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});
