<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\OrderController;

Route::apiResource('clients', ClientController::class);
Route::apiResource('vehicles', VehicleController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('orders', OrderController::class);

// Papildomi maršrutai specifiniams atvejams
Route::get('clients/{client}/vehicles', [ClientController::class, 'vehicles']);
Route::get('vehicles/{vehicle}/orders', [VehicleController::class, 'orders']);