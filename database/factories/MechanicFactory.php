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
            'vardas' => $this->faker->firstName(),
            'pavarde' => $this->faker->lastName(),
            'specializacija' => $this->faker->randomElement([
                'Variklio specialistas',
                'Elektrikas',
                'Važiuoklės specialistas',
                'Kėbulo specialistas',
                'Diagnostikos specialistas'
            ]),
            'tel_numeris' => $this->faker->phoneNumber(),
            'patirtis_metais' => $this->faker->numberBetween(1, 30),
        ];
    }
} 