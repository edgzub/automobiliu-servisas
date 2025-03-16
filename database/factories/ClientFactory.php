<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'vardas' => $this->faker->firstName(),
            'pavarde' => $this->faker->lastName(),
            'tel_numeris' => $this->faker->phoneNumber(),
            'el_pastas' => $this->faker->unique()->safeEmail(),
            // Nėra 'adresas' lauko, nors migracija egzistuoja
        ];
    }
}