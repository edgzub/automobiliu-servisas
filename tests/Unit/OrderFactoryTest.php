<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\OrderFactory;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

#[TestClass]
class OrderFactoryTest extends TestCase
{
    use RefreshDatabase;

    private OrderFactory $orderFactory;

    #[SetUp]
    protected function setUp(): void
    {
        parent::setUp();
        $this->orderFactory = new OrderFactory();
    }

    #[Test]
    public function creates_order_with_valid_data(): void
    {
        // Arrange
        $vehicle = Vehicle::factory()->create();
        $service = Service::factory()->create([
            'kaina' => 100.00
        ]);
        
        $data = [
            'vehicle_id' => $vehicle->id,
            'service_id' => $service->id,
            'data' => now(),
            'statusas' => 'laukiama',
            'kaina' => 100.00
        ];

        // Act
        $order = $this->orderFactory->createOrder($data);

        // Assert
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($vehicle->id, $order->vehicle_id);
        $this->assertEquals($service->id, $order->service_id);
        $this->assertEquals('laukiama', $order->statusas);
        $this->assertEquals(100.00, $order->kaina);
    }

    #[Test]
    public function creates_emergency_order(): void
    {
        // Arrange
        $vehicle = Vehicle::factory()->create();
        $service = Service::factory()->create([
            'kaina' => 100.00
        ]);

        // Act
        $order = $this->orderFactory->createEmergencyOrder($vehicle, $service);

        // Assert
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals('vykdoma', $order->statusas);
        $this->assertEquals(150.00, $order->kaina);
        $this->assertStringContainsString('Skubus', $order->komentarai);
    }

    #[Test]
    public function throws_exception_for_invalid_vehicle(): void
    {
        // Arrange
        $service = Service::factory()->create();
        
        $data = [
            'vehicle_id' => 99999,
            'service_id' => $service->id,
            'data' => now()
        ];

        // Assert & Act
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $this->orderFactory->createOrder($data);
    }

    #[Test]
    #[DataProvider('orderStatusProvider')]
    public function creates_order_with_different_statuses(string $status): void
    {
        // Arrange
        $vehicle = Vehicle::factory()->create();
        $service = Service::factory()->create();
        
        $data = [
            'vehicle_id' => $vehicle->id,
            'service_id' => $service->id,
            'data' => now(),
            'statusas' => $status
        ];

        // Act
        $order = $this->orderFactory->createOrder($data);

        // Assert
        $this->assertEquals($status, $order->statusas);
    }

    public static function orderStatusProvider(): array
    {
        return [
            'waiting' => ['laukiama'],
            'in_progress' => ['vykdoma'],
            'completed' => ['atlikta'],
            'cancelled' => ['atsaukta']
        ];
    }
}