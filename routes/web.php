<?php

use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Vehicle\VehicleController;
use App\Http\Controllers\Service\ServiceController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Pagrindinis puslapis
Route::get('/', function () {
    return Inertia::render('Dashboard/Index');
});

// Autentifikacijos maršrutai
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Apsaugoti maršrutai
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // CRUD maršrutai su pilnais namespace keliais
    Route::resource('clients', ClientController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('orders', OrderController::class);
    Route::post('/vehicles/import', [VehicleController::class, 'importFromApi'])->name('vehicles.import');
});