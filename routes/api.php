<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TimeSlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ReviewController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('doctors', DoctorController::class);
        Route::apiResource('services', ServiceController::class);
        Route::apiResource('time-slots', TimeSlotController::class);
    });

    Route::middleware('role:client')->group(function () {
        Route::apiResource('appointments', AppointmentController::class)
            ->only(['index', 'show', 'store', 'update', 'destroy']);
        Route::post('appointments/{appointment}/cancel', [AppointmentController::class, 'cancel'])
            ->name('appointments.cancel');
    });

    Route::middleware('role:doctor')->group(function () {
        Route::get('doctor/appointments', [AppointmentController::class, 'indexForDoctor']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('reviews', ReviewController::class);
    });
});

