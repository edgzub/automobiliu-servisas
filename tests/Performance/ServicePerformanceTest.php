<?php

namespace Tests\Performance;

use Tests\TestCase;
use App\Services\SearchService;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class ServicePerformanceTest extends TestCase
{
    use RefreshDatabase;

    private $searchService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->searchService = new SearchService();
    }

    public function test_search_performance()
    {
        // Arrange
        Order::factory()->count(100)->create();
        $startTime = microtime(true);

        // Act
        $this->searchService->searchByKeyword('BMW');
        
        // Assert
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        $this->assertLessThan(1.0, $executionTime, 'Paieška užtruko per ilgai');
    }

    public function test_database_query_performance()
    {
        DB::connection()->enableQueryLog();
        
        $this->searchService->filterOrders(['status' => 'active']);
        
        $queries = DB::getQueryLog();
        $this->assertLessThan(5, count($queries), 'Per daug DB užklausų');
    }
} 