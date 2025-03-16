<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition()
    {
        return [
            'client_id' => Client::factory(),
            'marke' => $this->faker->randomElement(['BMW', 'Audi', 'Mercedes', 'Toyota', 'Honda', 'Volkswagen', 'Ford']),
            'modelis' => $this->faker->word(),
            'metai' => $this->faker->numberBetween(2000, 2023),
            'valstybinis_numeris' => strtoupper($this->faker->bothify('???###')),
            'vin_kodas' => strtoupper($this->faker->regexify('[A-HJ-NPR-Z0-9]{17}')),
        ];
    }
} 