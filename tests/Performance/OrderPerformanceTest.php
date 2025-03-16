<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Car;
use App\Models\Service;
use App\Models\Mechanic;
use App\Services\SearchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class OrderPerformanceTest extends TestCase
{
    use RefreshDatabase;
    
    private $searchService;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = new SearchService();
    }
    
    public function testOrderCreationPerformance()
    {
        // Arrange
        $car = Car::factory()->create();
        $service = Service::factory()->create();
        $mechanic = Mechanic::factory()->create();
        
        $orderData = [
            'car_id' => $car->id,
            'service_id' => $service->id,
            'mechanic_id' => $mechanic->id,
            'status' => 'new',
            'total_price' => 100.00,
            'description' => 'Test order',
            'completion_date' => now()->addDays(7),
        ];
        
        // Act
        $startTime = microtime(true);
        
        Order::create($orderData);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(0.5, $executionTime, 'Order creation took too long: ' . $executionTime . ' seconds');
    }
    
    public function testSearchPerformance()
    {
        // Arrange
        Order::factory(50)->create();
        
        // Act
        $startTime = microtime(true);
        
        $this->searchService->searchByKeyword('test');
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(1.0, $executionTime, 'Search operation took too long: ' . $executionTime . ' seconds');
    }
    
    public function testFilterPerformance()
    {
        // Arrange
        Order::factory(50)->create();
        $filters = ['status' => 'new'];
        
        // Act
        $startTime = microtime(true);
        
        $this->searchService->filterOrders($filters);
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(1.0, $executionTime, 'Filter operation took too long: ' . $executionTime . ' seconds');
    }
    
    public function testDatabaseQueryPerformance()
    {
        // Arrange
        Order::factory(50)->create();
        DB::connection()->enableQueryLog();
        
        // Act
        $startTime = microtime(true);
        
        Order::with(['car', 'service', 'mechanic', 'part'])->limit(20)->get();
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $queries = DB::getQueryLog();
        
        // Assert
        $this->assertLessThan(1.0, $executionTime, 'Database query took too long: ' . $executionTime . ' seconds');
        $this->assertLessThan(6, count($queries), 'Too many database queries: ' . count($queries));
    }
} 