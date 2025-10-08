<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicationController;

// Form routes
Route::get('/', [ApplicationController::class, 'create'])->name('application.create');
Route::post('/apply', [ApplicationController::class, 'store'])->name('application.store');

// Razorpay callback route (public, as Razorpay needs to hit it)
Route::post('/payment/callback', [ApplicationController::class, 'callback'])->name('payment.callback');

// Dashboard routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ApplicationController::class, 'dashboard'])->name('application.dashboard');
});


require __DIR__.'/auth.php';
