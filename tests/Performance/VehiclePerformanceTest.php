<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\Order;
use App\Services\VehicleDataAdapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class VehiclePerformanceTest extends TestCase
{
    use RefreshDatabase;
    
    private VehicleDataAdapter $adapter;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->adapter = new VehicleDataAdapter();
    }
    
    /**
     * @test
     * @group performance
     */
    public function vehicleCreationPerformance()
    {
        // Arrange
        $client = Client::factory()->create();
        
        $vehicleData = [
            'client_id' => $client->id,
            'marke' => 'BMW',
            'modelis' => 'X5',
            'metai' => 2020,
            'valstybinis_numeris' => 'ABC123',
            'vin_kodas' => '1HGCM82633A123456'
        ];
        
        // Act
        $startTime = microtime(true);
        
        Vehicle::create($vehicleData);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(0.5, $executionTime, 'Vehicle creation took too long: ' . $executionTime . ' seconds');
    }
    
    /**
     * @test
     * @group performance
     */
    public function vehicleQueryPerformance()
    {
        // Arrange - create many vehicles
        $client = Client::factory()->create();
        Vehicle::factory(50)->create(['client_id' => $client->id]);
        
        // Act
        $startTime = microtime(true);
        
        $vehicles = Vehicle::where('client_id', $client->id)->get();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(0.5, $executionTime, 'Vehicle query took too long: ' . $executionTime . ' seconds');
        $this->assertEquals(50, $vehicles->count(), 'Should have retrieved 50 vehicles');
    }
    
    /**
     * @test
     * @group performance
     */
    public function vehicleWithRelationshipsPerformance()
    {
        // Arrange
        $client = Client::factory()->create();
        $vehicles = Vehicle::factory(10)->create(['client_id' => $client->id]);
        
        // Create orders for each vehicle
        foreach ($vehicles as $vehicle) {
            Order::factory(5)->create(['vehicle_id' => $vehicle->id]);
        }
        
        DB::connection()->enableQueryLog();
        
        // Act
        $startTime = microtime(true);
        
        $vehiclesWithOrders = Vehicle::with('orders')->where('client_id', $client->id)->get();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $queries = DB::getQueryLog();
        
        // Assert
        $this->assertLessThan(0.5, $executionTime, 'Vehicle with relationships query took too long: ' . $executionTime . ' seconds');
        $this->assertLessThan(5, count($queries), 'Too many database queries: ' . count($queries));
        $this->assertEquals(10, $vehiclesWithOrders->count(), 'Should have retrieved 10 vehicles');
        
        // Check eager loading worked - should have all orders preloaded
        $firstVehicle = $vehiclesWithOrders->first();
        
        // This access shouldn't trigger an additional query because of eager loading
        $queryCountBefore = count(DB::getQueryLog());
        $orders = $firstVehicle->orders;
        $queryCountAfter = count(DB::getQueryLog());
        
        $this->assertEquals($queryCountBefore, $queryCountAfter, 'Accessing relationship should not trigger additional queries');
    }
    
    /**
     * @test
     * @group performance
     */
    public function dataAdapterPerformance()
    {
        // Arrange
        $client = Client::factory()->create();
        $vehicles = Vehicle::factory(20)->create(['client_id' => $client->id]);
        
        // Act
        $startTime = microtime(true);
        
        foreach ($vehicles as $vehicle) {
            $adapted = $this->adapter->adaptToExternalApi($vehicle);
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $averageTime = $executionTime / count($vehicles);
        
        // Assert
        $this->assertLessThan(0.1, $averageTime, 'Average time per adaptation too high: ' . $averageTime . ' seconds');
        $this->assertLessThan(1.0, $executionTime, 'Total adaptation time too high: ' . $executionTime . ' seconds');
    }
    
    /**
     * @test
     * @group performance
     */
    public function bulkVehicleOperationsPerformance()
    {
        // Arrange
        $client = Client::factory()->create();
        $startYear = 2010;
        
        // Act - bulk insert timing
        $startTime = microtime(true);
        
        // Create 20 vehicles in a batch
        $vehiclesData = [];
        for ($i = 0; $i < 20; $i++) {
            $vehiclesData[] = [
                'client_id' => $client->id,
                'marke' => 'Test Brand ' . $i,
                'modelis' => 'Test Model ' . $i,
                'metai' => $startYear + $i,
                'valstybinis_numeris' => 'TEST' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'vin_kodas' => '1HGCM82633A' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        DB::table('vehicles')->insert($vehiclesData);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(1.0, $executionTime, 'Bulk insert operation took too long: ' . $executionTime . ' seconds');
        $this->assertEquals(20, Vehicle::where('client_id', $client->id)->count(), 'Should have inserted 20 vehicles');
        
        // Act - bulk update timing
        $startTime = microtime(true);
        
        Vehicle::where('client_id', $client->id)
            ->update(['marke' => 'Updated Brand']);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(0.5, $executionTime, 'Bulk update operation took too long: ' . $executionTime . ' seconds');
        $this->assertEquals(20, Vehicle::where('marke', 'Updated Brand')->count(), 'Should have updated all 20 vehicles');
    }
} 