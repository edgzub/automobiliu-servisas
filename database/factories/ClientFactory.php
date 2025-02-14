<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'vardas' => fake('lt_LT')->firstName(),
            'pavarde' => fake('lt_LT')->lastName(),
            'tel_numeris' => fake('lt_LT')->phoneNumber(),
            'el_pastas' => fake()->unique()->safeEmail(),
            'registracijos_data' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}