<?php

use App\Http\Controllers\Guest\BookingController;
use App\Http\Controllers\Guest\ProductController as GuestProductController;
use App\Http\Controllers\Master\ProductController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::controller(BookingController::class)->group(function () {
    Route::get('/booking', 'index');
    Route::get('/booking-page', 'booking');
    Route::get('/transaction', 'data');
});

Route::controller(GuestProductController::class)->group(function () {
    Route::get('/product', 'data');
});

Route::get('/get-snap-token/{bookingId}', function ($bookingId) {
    $booking = Transaction::find($bookingId);

    if (!$booking || !$booking->snap_token) {
        return response()->json(['message' => 'Snap token tidak ditemukan'], 404);
    }

    return response()->json(['snapToken' => $booking->snap_token]);
});

require __DIR__ . '/auth.php';
