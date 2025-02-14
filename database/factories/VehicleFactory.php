<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    public function definition(): array
    {
        $carBrands = ['Audi', 'BMW', 'Volkswagen', 'Toyota', 'Ford', 'Mercedes-Benz', 'Volvo'];
        $brand = $this->faker->randomElement($carBrands);
        
        $models = [
            'Audi' => ['A3', 'A4', 'A6', 'Q5', 'Q7'],
            'BMW' => ['3 Series', '5 Series', 'X3', 'X5', 'M3'],
            'Volkswagen' => ['Golf', 'Passat', 'Tiguan', 'Arteon', 'T-Roc'],
            'Toyota' => ['Corolla', 'Camry', 'RAV4', 'Prius', 'C-HR'],
            'Ford' => ['Focus', 'Fiesta', 'Kuga', 'Mondeo', 'Puma'],
            'Mercedes-Benz' => ['C-Class', 'E-Class', 'GLC', 'A-Class', 'GLE'],
            'Volvo' => ['XC60', 'XC90', 'V60', 'S60', 'V90'],
        ];

        return [
            'client_id' => Client::factory(),
            'marke' => $brand,
            'modelis' => $this->faker->randomElement($models[$brand]),
            'metai' => $this->faker->numberBetween(2010, 2024),
            'valstybinis_numeris' => strtoupper($this->faker->bothify('???###')),
            'vin_kodas' => strtoupper($this->faker->bothify('??#??########???')),
        ];
    }
}