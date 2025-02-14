<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Order\OrderController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard/Index');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('clients', ClientController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('orders', OrderController::class);
});

require __DIR__.'/auth.php';