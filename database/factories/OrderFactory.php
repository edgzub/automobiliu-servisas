<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $service = Service::inRandomOrder()->first() ?: Service::factory()->create();
        
        return [
            'vehicle_id' => Vehicle::factory(),
            'service_id' => $service->id,
            'statusas' => $this->faker->randomElement(['1', '2', '3', '0']),
            'kaina' => $this->faker->randomFloat(2, 100, 2000),
            'komentarai' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}