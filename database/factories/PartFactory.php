<?php

namespace Database\Factories;

use App\Models\Part;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartFactory extends Factory
{
    protected $model = Part::class;

    public function definition()
    {
        return [
            'pavadinimas' => $this->faker->randomElement([
                'Stabdžių diskas',
                'Tepalų filtras',
                'Oro filtras',
                'Amortizatorius',
                'Akumuliatorius',
                'Variklio diržas',
                'Uždegimo žvakė',
                'Sankabos diskas'
            ]),
            'kodas' => strtoupper($this->faker->bothify('??###??')),
            'kaina' => $this->faker->randomFloat(2, 10, 1000),
            'kiekis_sandely' => $this->faker->numberBetween(0, 50),
            'gamintojas' => $this->faker->company(),
        ];
    }
} 