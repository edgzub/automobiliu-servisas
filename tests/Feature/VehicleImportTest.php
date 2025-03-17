<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Client;

class VehicleImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_import_vehicles_from_api()
    {
        // Sukuriame klientą testams
        $client = Client::factory()->create();
        
        // Bandome iškviesti importavimo maršrutą
        $response = $this->post(route('vehicles.import'), [
            'client_id' => $client->id
        ]);
        
        // Tikriname, ar nukreipiama atgal į sąrašą
        $response->assertRedirect(route('vehicles.index'));
        
        // Tikriname, ar duomenų bazėje yra automobilių
        $this->assertDatabaseCount('vehicles', '>', 0);
    }
} 