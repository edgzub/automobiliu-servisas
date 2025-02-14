<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        $services = [
            'Tepalų keitimas' => ['Pilnas tepalų ir filtrų keitimas', 50.00, 1.00, 'Techninė priežiūra'],
            'Ratų balansavimas' => ['Ratų balansavimas ir montavimas', 40.00, 1.00, 'Ratai'],
            'Stabdžių patikra' => ['Stabdžių sistemos patikra ir remontas', 80.00, 2.00, 'Stabdžiai'],
            'Variklio diagnostika' => ['Kompiuterinė variklio diagnostika', 60.00, 1.50, 'Diagnostika'],
            'Kondicionieriaus pildymas' => ['Kondicionieriaus sistemos pildymas', 70.00, 1.00, 'Klimato kontrolė'],
        ];

        $serviceName = $this->faker->randomElement(array_keys($services));
        $serviceData = $services[$serviceName];

        return [
            'pavadinimas' => $serviceName,
            'aprasymas' => $serviceData[0],
            'kaina' => $serviceData[1],
            'trukme_valandomis' => $serviceData[2],
            'kategorija' => $serviceData[3],
        ];
    }
}