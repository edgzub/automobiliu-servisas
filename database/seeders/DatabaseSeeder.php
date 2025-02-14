<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\Order;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sukuriame 20 klientų
        Client::factory(20)->create();

        // Sukuriame 30 automobilių
        Vehicle::factory(30)->create();

        // Sukuriame 20 paslaugų
        Service::factory(20)->create();

        // Sukuriame 40 užsakymų
        Order::factory(40)->create();
    }
}