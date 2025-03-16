<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Sukuriame 10 vartotojų
        User::factory(10)->create();

        // Sukuriame 25 paslaugas
        Service::factory(25)->create();

        // Sukuriame 25 klientus su automobiliais ir užsakymais
        $clients = Client::factory(25)->create();
        
        foreach ($clients as $client) {
            // 1-3 automobiliai kiekvienam klientui
            $vehicles = Vehicle::factory(rand(1, 3))->create([
                'client_id' => $client->id,
            ]);
            
            foreach ($vehicles as $vehicle) {
                // 1-3 užsakymai kiekvienam automobiliui
                for ($i = 0; $i < rand(1, 3); $i++) {
                    Order::factory()->create([
                        'vehicle_id' => $vehicle->id,
                        'service_id' => Service::inRandomOrder()->first()->id,
                    ]);
                }
            }
        }
    }
}