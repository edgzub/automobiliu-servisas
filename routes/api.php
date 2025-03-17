<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Palikite tik šį importą, o kitus užkomentuokite
// use App\Http\Controllers\Api\OrderApiController;
// use App\Http\Controllers\Api\ServiceApiController;
// use App\Http\Controllers\Api\MechanicApiController;
// use App\Http\Controllers\Api\CarApiController;
// use App\Http\Controllers\Api\PartApiController;
// use App\Http\Controllers\Api\ClientApiController;
use App\Http\Controllers\Api\CarDataController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API маршруты
Route::prefix('v1')->group(function () {
    // Užkomentuokite šiuos maršrutus, kol nesukursite kontrolerių
    // Route::apiResource('orders', OrderApiController::class);
    // Route::apiResource('services', ServiceApiController::class);
    // Route::apiResource('mechanics', MechanicApiController::class);
    // Route::apiResource('cars', CarApiController::class);
    // Route::apiResource('parts', PartApiController::class);
    // Route::apiResource('clients', ClientApiController::class);
    
    // Route::get('search', [OrderApiController::class, 'search']);
    // Route::get('reports/mechanics', [OrderApiController::class, 'mechanicReport']);
    // Route::get('reports/services', [OrderApiController::class, 'serviceReport']);
    
    // Palikite tik šiuos automobilių duomenų maršrutus
    Route::get('cars/makes', [CarDataController::class, 'makes']);
    Route::get('cars/models/{make}', [CarDataController::class, 'models']);
    Route::get('cars/years', [CarDataController::class, 'years']);
    Route::get('cars/decode-vin/{vin}', [CarDataController::class, 'decodeVin']);
});