<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('auth')->group(function () {
    Route::post('/login',   [AuthController::class, 'login']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('/logout',  [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('/me',       [AuthController::class, 'me'])->middleware('auth:api');
});

Route::middleware(['auth:api'])->group(function () {
    Route::get('events', [EventController::class, 'index']);
    Route::get('events/{event}', [EventController::class, 'show']);

    Route::middleware('role:organizer|admin')->group(function () {
        Route::post('events', [EventController::class, 'store']);
        Route::put('events/{event}', [EventController::class, 'update']);
        Route::delete('events/{event}', [EventController::class, 'destroy']);
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{user}', [UserController::class, 'show']);
        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);
        Route::post('users/{user}/block', [UserController::class, 'block']);
    });

    Route::middleware('role:user')->group(function () {
        Route::post('bookings', [BookingController::class, 'store']);
        Route::get('my-bookings', [BookingController::class, 'userBookings']);
    });

    Route::middleware('role:admin|organizer')->group(function () {
        Route::get('bookings', [BookingController::class, 'index']);
        Route::get('bookings/{booking}', [BookingController::class, 'show']);
        Route::put('bookings/{booking}', [BookingController::class, 'update']);
        Route::delete('bookings/{booking}', [BookingController::class, 'destroy']);
    });
});
