<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Service;

class OrderFactory
{
    public function createOrder(array $data): Order
    {
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);
        $service = Service::findOrFail($data['service_id']);

        return Order::create([
            'vehicle_id' => $vehicle->id,
            'service_id' => $service->id,
            'data' => $data['data'],
            'statusas' => $data['statusas'] ?? 'laukiama',
            'komentarai' => $data['komentarai'] ?? null,
            'kaina' => $data['kaina'] ?? $service->kaina,
        ]);
    }

    public function createEmergencyOrder(Vehicle $vehicle, Service $service): Order
    {
        return Order::create([
            'vehicle_id' => $vehicle->id,
            'service_id' => $service->id,
            'data' => now(),
            'statusas' => 'vykdoma',
            'komentarai' => 'Skubus užsakymas',
            'kaina' => $service->kaina * 1.5, // Skubaus užsakymo antkainis
        ]);
    }
}