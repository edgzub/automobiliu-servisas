<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'pavadinimas' => $this->faker->randomElement([
                'Tepalų keitimas',
                'Ratų balansavimas',
                'Stabdžių patikra',
                'Variklio diagnostika',
                'Važiuoklės remontas',
                'Kondicionieriaus pildymas',
                'Elektros sistemos remontas',
                'Kompiuterinė diagnostika'
            ]),
            'aprasymas' => $this->faker->paragraph(),
            'kaina' => $this->faker->randomFloat(2, 50, 500),
            'trukme_valandomis' => $this->faker->numberBetween(1, 8),
            'kategorija' => $this->faker->randomElement([
                'Variklis',
                'Važiuoklė',
                'Elektros sistema',
                'Kėbulas',
                'Tepalai ir skysčiai'
            ]),
        ];
    }
}