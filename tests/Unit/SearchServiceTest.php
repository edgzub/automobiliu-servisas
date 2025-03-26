<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\SearchService;
use App\Models\Order;
use App\Models\Service;
use App\Models\Mechanic;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class SearchServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private SearchService $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = new SearchService();
    }

    /**
     * @test
     * @group search
     */
    public function searchByKeyword_returns_matching_orders()
    {
        // Arrange
        $keyword = 'TestKeyword';
        
        $mechanic = Mechanic::factory()->create([
            'name' => 'Regular Mechanic'
        ]);
        
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Regular Car'
        ]);
        
        $service = Service::factory()->create([
            'name' => 'Regular Service'
        ]);
        
        // Create an order with the keyword in description
        Order::factory()->create([
            'description' => "Order with {$keyword} in description",
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'service_id' => $service->id
        ]);
        
        // Create an order without the keyword
        Order::factory()->create([
            'description' => 'Regular order description',
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'service_id' => $service->id
        ]);
        
        // Act
        $result = $this->searchService->searchByKeyword($keyword);
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(1, $result);
        $this->assertStringContainsString($keyword, $result->first()->description);
    }

    /**
     * @test
     * @group search
     */
    public function searchByKeyword_finds_matches_in_related_models()
    {
        // Arrange
        $keyword = 'SpecialKeyword';
        
        $mechanic = Mechanic::factory()->create([
            'name' => "Mechanic with {$keyword}"
        ]);
        
        $vehicle = Vehicle::factory()->create([
            'brand' => 'Regular Car'
        ]);
        
        $service = Service::factory()->create([
            'name' => 'Regular Service'
        ]);
        
        // Create an order with the keyword in the mechanic name
        Order::factory()->create([
            'description' => 'Regular description',
            'vehicle_id' => $vehicle->id,
            'mechanic_id' => $mechanic->id,
            'service_id' => $service->id
        ]);
        
        // Act
        $result = $this->searchService->searchByKeyword($keyword);
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertNotEmpty($result);
        $this->assertTrue($result->count() > 0);
    }

    /**
     * @test
     * @group search
     * @dataProvider filtersProvider
     */
    public function filterOrders_returns_filtered_orders($filters, $expectedCount)
    {
        // Arrange
        $mechanic1 = Mechanic::factory()->create();
        $mechanic2 = Mechanic::factory()->create();
        
        $vehicle1 = Vehicle::factory()->create();
        $vehicle2 = Vehicle::factory()->create();
        
        $service1 = Service::factory()->create();
        $service2 = Service::factory()->create();
        
        // Create orders with different properties
        Order::factory()->create([
            'status' => 'completed',
            'mechanic_id' => $mechanic1->id,
            'vehicle_id' => $vehicle1->id,
            'service_id' => $service1->id,
            'total_price' => 100.00,
            'created_at' => Carbon::now()->subDays(10)
        ]);
        
        Order::factory()->create([
            'status' => 'in_progress',
            'mechanic_id' => $mechanic2->id,
            'vehicle_id' => $vehicle2->id,
            'service_id' => $service2->id,
            'total_price' => 200.00,
            'created_at' => Carbon::now()->subDays(5)
        ]);
        
        // Act
        $result = $this->searchService->filterOrders($filters);
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertNotEmpty($result);
        
        $this->assertCount($expectedCount, $result);
    }

    public static function filtersProvider()
    {
        return [
            'status filter' => [
                ['status' => 'completed'],
                1
            ],
            'date filter' => [
                ['date_from' => now()->subDays(15), 'date_to' => now()],
                2
            ],
            'price filter' => [
                ['price_from' => 50, 'price_to' => 150],
                1
            ],
            'combined filters' => [
                [
                    'status' => 'completed',
                    'date_from' => now()->subDays(15),
                    'price_from' => 50,
                    'price_to' => 150
                ],
                1
            ]
        ];
    }

    /**
     * @test
     * @group search
     */
    public function sortOrders_returns_sorted_orders_by_id_asc()
    {
        // Arrange
        Order::factory()->create([
            'total_price' => 100.00,
            'created_at' => Carbon::now()->subDays(10)
        ]);
        
        Order::factory()->create([
            'total_price' => 200.00,
            'created_at' => Carbon::now()->subDays(5)
        ]);
        
        Order::factory()->create([
            'total_price' => 150.00,
            'created_at' => Carbon::now()->subDays(7)
        ]);
        
        // Act
        $result = $this->searchService->sortOrders('id', 'asc');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        
        // Check sorting order
        $values = $result->pluck('id')->toArray();
        
        $sortedValues = $values;
        sort($sortedValues);
        $this->assertEquals($sortedValues, $values);
    }
    
    /**
     * @test
     * @group search
     */
    public function sortOrders_returns_sorted_orders_by_created_at_desc()
    {
        // Arrange
        Order::factory()->create([
            'total_price' => 100.00,
            'created_at' => Carbon::now()->subDays(10)
        ]);
        
        Order::factory()->create([
            'total_price' => 200.00,
            'created_at' => Carbon::now()->subDays(5)
        ]);
        
        Order::factory()->create([
            'total_price' => 150.00,
            'created_at' => Carbon::now()->subDays(7)
        ]);
        
        // Act
        $result = $this->searchService->sortOrders('created_at', 'desc');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        
        // Check sorting order
        $values = $result->pluck('created_at')->toArray();
        
        $sortedValues = $values;
        rsort($sortedValues);
        $this->assertEquals($sortedValues, $values);
    }
    
    /**
     * @test
     * @group search
     */
    public function sortOrders_returns_sorted_orders_by_total_price_asc()
    {
        // Arrange
        Order::factory()->create([
            'total_price' => 100.00,
            'created_at' => Carbon::now()->subDays(10)
        ]);
        
        Order::factory()->create([
            'total_price' => 200.00,
            'created_at' => Carbon::now()->subDays(5)
        ]);
        
        Order::factory()->create([
            'total_price' => 150.00,
            'created_at' => Carbon::now()->subDays(7)
        ]);
        
        // Act
        $result = $this->searchService->sortOrders('total_price', 'asc');
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(3, $result);
        
        // Check sorting order
        $values = $result->pluck('total_price')->toArray();
        
        $sortedValues = $values;
        sort($sortedValues);
        $this->assertEquals($sortedValues, $values);
    }

    /**
     * @test
     * @group search
     * @group exceptions
     */
    public function sortOrders_with_invalid_column_defaults_to_created_at()
    {
        // Arrange
        Order::factory(3)->create();
        
        // Act
        $result = $this->searchService->sortOrders('invalid_column', 'desc');
        $expectedResult = $this->searchService->sortOrders('created_at', 'desc');
        
        // Assert - should be the same as sorting by created_at
        $this->assertEquals(
            $expectedResult->pluck('id')->toArray(),
            $result->pluck('id')->toArray()
        );
    }
    
    /**
     * @test
     * @group performance
     */
    public function searchByKeyword_performance_test()
    {
        // Arrange
        $keyword = 'PerformanceTest';
        Order::factory(30)->create();
        
        Order::factory()->create([
            'description' => "Order with {$keyword}"
        ]);
        
        // Act
        $startTime = microtime(true);
        $this->searchService->searchByKeyword($keyword);
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(
            1.0, 
            $executionTime, 
            "searchByKeyword took too long: {$executionTime} seconds"
        );
    }
    
    /**
     * @test
     * @group performance
     */
    public function filterOrders_performance_test()
    {
        // Arrange
        Order::factory(30)->create(['status' => 'completed']);
        Order::factory(20)->create(['status' => 'in_progress']);
        
        // Act
        $startTime = microtime(true);
        $this->searchService->filterOrders(['status' => 'completed']);
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(
            0.5, 
            $executionTime, 
            "filterOrders took too long: {$executionTime} seconds"
        );
    }
} 