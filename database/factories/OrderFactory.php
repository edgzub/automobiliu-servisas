<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $service = Service::factory()->create();
        return [
            'vehicle_id' => Vehicle::factory(),
            'service_id' => $service->id,
            'data' => fake()->dateTimeBetween('-6 months', '+1 month'),
            'statusas' => fake()->randomElement(['laukiama', 'vykdoma', 'atlikta', 'atsaukta']),
            'komentarai' => fake()->optional(0.7)->text(200),
            'kaina' => $service->kaina,
        ];
    }
}