<?php

use App\Http\Controllers\AuthWebController;
use App\Http\Controllers\ClientAppointmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuestBookingController;
use App\Http\Controllers\InfoPageController;
use App\Http\Controllers\ReviewsController;
use App\Http\Controllers\SpecialistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/specialists', [SpecialistController::class, 'index'])->name('specialists.index');
Route::get('/info/{slug}', [InfoPageController::class, 'show'])->name('info.show');
Route::get('/reviews', [ReviewsController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewsController::class, 'store'])->name('reviews.store');

Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);

Route::get('/register', [AuthWebController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthWebController::class, 'register']);

Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

// Гостевая запись без регистрации
Route::get('/booking', [GuestBookingController::class, 'showForm'])->name('guest.booking.form');
Route::post('/booking', [GuestBookingController::class, 'store'])->name('guest.booking.store');
Route::get('/booking/success', function () {
    if (! session()->has('appointment_created')) {
        return redirect()->route('home');
    }

    return view('booking.success', [
        'appointment' => session('appointment_created'),
        'guest' => true,
    ]);
})->name('guest.booking.success');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:client')->group(function () {
        Route::post('/client/appointments', [ClientAppointmentController::class, 'store'])
            ->name('client.appointments.store');
        Route::get('/dashboard/booking/success', function () {
            if (! session()->has('appointment_created')) {
                return redirect()->route('dashboard');
            }

            return view('booking.success', [
                'appointment' => session('appointment_created'),
                'guest' => false,
            ]);
        })->name('client.booking.success');
    });
});
