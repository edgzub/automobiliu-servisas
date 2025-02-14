<?php

namespace App\Services;

use App\Models\Vehicle;
use InvalidArgumentException;

class VehicleDataAdapter
{
    public function adaptToExternalApi(Vehicle $vehicle): array
    {
        if (!$vehicle->client) {
            throw new InvalidArgumentException('Vehicle must have an associated client');
        }

        return [
            'registration_number' => $vehicle->valstybinis_numeris,
            'vin' => $vehicle->vin_kodas,
            'make' => $vehicle->marke,
            'model' => $vehicle->modelis,
            'year' => $vehicle->metai,
            'owner' => [
                'first_name' => $vehicle->client->vardas,
                'last_name' => $vehicle->client->pavarde,
                'phone' => $vehicle->client->tel_numeris,
                'email' => $vehicle->client->el_pastas
            ]
        ];
    }

    public function adaptFromExternalApi(array $data): array
    {
        $requiredFields = ['registration_number', 'vin', 'make', 'model', 'year'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
        }

        if (strlen($data['vin']) !== 17) {
            throw new InvalidArgumentException('Invalid VIN code length');
        }

        if ($data['year'] < 1900 || $data['year'] > date('Y') + 1) {
            throw new InvalidArgumentException('Invalid year value');
        }

        return [
            'valstybinis_numeris' => strtoupper($data['registration_number']),
            'vin_kodas' => strtoupper($data['vin']),
            'marke' => ucfirst(strtolower($data['make'])),
            'modelis' => ucfirst(strtolower($data['model'])),
            'metai' => (int)$data['year']
        ];
    }
}