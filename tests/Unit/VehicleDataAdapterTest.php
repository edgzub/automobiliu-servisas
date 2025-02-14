<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\VehicleDataAdapter;
use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;

#[TestClass]
class VehicleDataAdapterTest extends TestCase
{
    use RefreshDatabase;

    private VehicleDataAdapter $adapter;
    private Vehicle $vehicle;
    private Client $client;

    #[SetUp]
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adapter = new VehicleDataAdapter();

        // Sukuriame klientą su unikaliu el. paštu
        $this->client = Client::factory()->create([
            'vardas' => 'Jonas',
            'pavarde' => 'Jonaitis',
            'tel_numeris' => '+37061234567',
            'el_pastas' => 'test_' . uniqid() . '@example.com'
        ]);

        // Sukuriame automobilį
        $this->vehicle = Vehicle::factory()->create([
            'client_id' => $this->client->id,
            'marke' => 'BMW',
            'modelis' => 'X5',
            'metai' => 2020,
            'valstybinis_numeris' => 'ABC123',
            'vin_kodas' => '1HGCM82633A123456'
        ]);
    }

    #[Test]
    public function adapts_vehicle_to_external_api_format(): void
    {
        $adapted = $this->adapter->adaptToExternalApi($this->vehicle);

        $this->assertIsArray($adapted);
        $this->assertEquals('ABC123', $adapted['registration_number']);
        $this->assertEquals('1HGCM82633A123456', $adapted['vin']);
        $this->assertEquals('BMW', $adapted['make']);
        $this->assertEquals('X5', $adapted['model']);
        $this->assertEquals(2020, $adapted['year']);
        
        $this->assertArrayHasKey('owner', $adapted);
        $this->assertEquals('Jonas', $adapted['owner']['first_name']);
        $this->assertEquals('Jonaitis', $adapted['owner']['last_name']);
        $this->assertEquals('+37061234567', $adapted['owner']['phone']);
    }

    #[Test]
    public function adapts_external_data_to_vehicle_format(): void
    {
        $externalData = [
            'registration_number' => 'XYZ789',
            'vin' => '2FMZA5142XBA69215',
            'make' => 'Audi',
            'model' => 'A6',
            'year' => 2021
        ];

        $adapted = $this->adapter->adaptFromExternalApi($externalData);

        $this->assertIsArray($adapted);
        $this->assertEquals('XYZ789', $adapted['valstybinis_numeris']);
        $this->assertEquals('2FMZA5142XBA69215', $adapted['vin_kodas']);
        $this->assertEquals('Audi', $adapted['marke']);
        $this->assertEquals('A6', $adapted['modelis']);
        $this->assertEquals(2021, $adapted['metai']);
    }

    #[Test]
    public function throws_exception_for_invalid_external_data(): void
    {
        $invalidData = [
            'registration_number' => 'XYZ789',
            'make' => 'Audi',
            'model' => 'A6',
            'year' => 2021
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->adapter->adaptFromExternalApi($invalidData);
    }

    #[Test]
    public function throws_exception_for_invalid_year(): void
    {
        $invalidData = [
            'registration_number' => 'XYZ789',
            'vin' => '2FMZA5142XBA69215',
            'make' => 'Audi',
            'model' => 'A6',
            'year' => 1800
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->adapter->adaptFromExternalApi($invalidData);
    }

    #[Test]
    public function throws_exception_for_invalid_vin(): void
    {
        $invalidData = [
            'registration_number' => 'XYZ789',
            'vin' => '12345',
            'make' => 'Audi',
            'model' => 'A6',
            'year' => 2021
        ];

        $this->expectException(InvalidArgumentException::class);
        $this->adapter->adaptFromExternalApi($invalidData);
    }
}