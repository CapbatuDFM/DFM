<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

// Every authenticated page goes through `role` (any active user); `role:owner` narrows it.
Route::middleware('role')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // Module entry points. Step 2+ replaces each Route::view with a real resource controller,
    // keeping these route names so the sidebar never changes.
    Route::view('/inventory', 'placeholder', ['title' => 'Inventory'])->name('inventory.index');
    Route::view('/residents', 'placeholder', ['title' => 'Residents'])->name('residents.index');
    Route::view('/rooms', 'placeholder', ['title' => 'Rooms'])->name('rooms.index');
    Route::view('/waiting-list', 'placeholder', ['title' => 'Waiting List'])->name('waiting-list.index');
    Route::view('/payments', 'placeholder', ['title' => 'Rental Payments'])->name('payments.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('role:owner')->group(function () {
        Route::resource('users', UserController::class)->except(['show', 'destroy']);
    });
});

require __DIR__.'/auth.php';
