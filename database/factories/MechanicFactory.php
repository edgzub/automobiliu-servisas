<?php

namespace Database\Factories;

use App\Models\Mechanic;
use Illuminate\Database\Eloquent\Factories\Factory;

class MechanicFactory extends Factory
{
    protected $model = Mechanic::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName(),
            'specialization' => $this->faker->randomElement([
                'Engine specialist',
                'Electrician',
                'Suspension specialist',
                'Body specialist',
                'Diagnostics specialist'
            ]),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->email(),
        ];
    }
} 