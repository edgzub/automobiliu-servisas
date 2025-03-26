<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ReportingService;
use App\Models\Order;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Mechanic;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Collection;

class ReportingServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private ReportingService $reportingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reportingService = new ReportingService();
    }

    /**
     * @test
     * @group reporting
     */
    public function getMechanicWorkload_returns_collection()
    {
        // Arrange
        $mechanic = Mechanic::factory()->create([
            'name' => 'Test Mechanic',
            'specialization' => 'Engine'
        ]);
        
        Order::factory()->create([
            'mechanic_id' => $mechanic->id,
            'status' => 'completed',
            'total_price' => 100.00
        ]);
        
        Order::factory()->create([
            'mechanic_id' => $mechanic->id,
            'status' => 'in_progress',
            'total_price' => 150.00
        ]);
        
        // Act
        $result = $this->reportingService->getMechanicWorkload();
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result);
        $this->assertEquals($mechanic->id, $result->first()->id);
        $this->assertEquals('Test Mechanic', $result->first()->name);
        $this->assertEquals(2, $result->first()->total_orders);
        $this->assertEquals(250.00, $result->first()->total_earnings);
        $this->assertEquals(1, $result->first()->completed_orders);
        $this->assertEquals(1, $result->first()->in_progress_orders);
    }

    /**
     * @test
     * @group reporting
     */
    public function getServicePopularity_returns_collection()
    {
        // Arrange
        $service = Service::factory()->create([
            'name' => 'Oil Change'
        ]);
        
        Order::factory(3)->create([
            'service_id' => $service->id,
            'total_price' => 50.00
        ]);
        
        // Act
        $result = $this->reportingService->getServicePopularity();
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertNotEmpty($result);
        $this->assertArrayHasKey('total_orders', $result->first());
        $this->assertEquals(3, $result->first()->total_orders);
        $this->assertEquals(150.00, $result->first()->total_revenue);
        $this->assertEquals(50.00, $result->first()->average_order_price);
    }

    /**
     * @test
     * @dataProvider periodProvider
     * @group reporting
     */
    public function getOrderStatistics_returns_correct_format_for_period($period, $expectedFormat)
    {
        // Arrange
        Order::factory(5)->create([
            'created_at' => Carbon::now()->subDays(5),
            'status' => 'completed'
        ]);
        
        Order::factory(3)->create([
            'created_at' => Carbon::now()->subDays(2),
            'status' => 'cancelled'
        ]);
        
        // Act
        $result = $this->reportingService->getOrderStatistics($period);
        
        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertNotEmpty($result);
        
        // Test that the period format matches our expectation based on the period parameter
        $firstPeriod = $result->first()->period;
        
        // Different assertions based on period format
        if ($period === 'day') {
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}-\d{2}$/', $firstPeriod);
        } elseif ($period === 'month') {
            $this->assertMatchesRegularExpression('/^\d{4}-\d{2}$/', $firstPeriod);
        } elseif ($period === 'year') {
            $this->assertMatchesRegularExpression('/^\d{4}$/', $firstPeriod);
        }
        
        // Assert the statistics are correct
        $totalOrders = $result->sum('total_orders');
        $this->assertEquals(8, $totalOrders);
        $this->assertEquals(5, $result->sum('completed_orders'));
        $this->assertEquals(3, $result->sum('cancelled_orders'));
    }
    
    /**
     * @test
     * @group performance
     */
    public function getOrderStatistics_day_performance_test()
    {
        // Arrange
        Order::factory(20)->create();
        
        // Act
        $startTime = microtime(true);
        $this->reportingService->getOrderStatistics('day');
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(
            0.5, 
            $executionTime, 
            "getOrderStatistics with period 'day' took too long: {$executionTime} seconds"
        );
    }
    
    /**
     * @test
     * @group performance
     */
    public function getOrderStatistics_month_performance_test()
    {
        // Arrange
        Order::factory(20)->create();
        
        // Act
        $startTime = microtime(true);
        $this->reportingService->getOrderStatistics('month');
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(
            0.3, 
            $executionTime, 
            "getOrderStatistics with period 'month' took too long: {$executionTime} seconds"
        );
    }
    
    /**
     * @test
     * @group performance
     */
    public function getOrderStatistics_year_performance_test()
    {
        // Arrange
        Order::factory(20)->create();
        
        // Act
        $startTime = microtime(true);
        $this->reportingService->getOrderStatistics('year');
        $endTime = microtime(true);
        
        $executionTime = ($endTime - $startTime);
        
        // Assert
        $this->assertLessThan(
            0.2, 
            $executionTime, 
            "getOrderStatistics with period 'year' took too long: {$executionTime} seconds"
        );
    }
    
    public static function periodProvider()
    {
        return [
            'day period' => ['day', '%Y-%m-%d'],
            'week period' => ['week', '%x-%v'],
            'month period' => ['month', '%Y-%m'],
            'year period' => ['year', '%Y']
        ];
    }
    
    /**
     * @test
     * @group exceptions
     */
    public function getOrderStatistics_with_invalid_period_defaults_to_month()
    {
        // Arrange
        Order::factory(5)->create();
        
        // Act
        $result = $this->reportingService->getOrderStatistics('invalid_period');
        $monthResult = $this->reportingService->getOrderStatistics('month');
        
        // Assert - should be the same as month results
        $this->assertEquals(
            $monthResult->pluck('period')->toArray(),
            $result->pluck('period')->toArray()
        );
    }
} 