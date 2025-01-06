<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);

    Route::resource('rooms', RoomController::class);
    Route::put('/rooms/{id}/approve', [RoomController::class, 'approve'])->name('rooms.approve');
    Route::put('/rooms/{id}/reject', [RoomController::class, 'reject'])->name('rooms.reject');

    Route::resource('bookings', BookingController::class);
    
});

require __DIR__.'/auth.php';
