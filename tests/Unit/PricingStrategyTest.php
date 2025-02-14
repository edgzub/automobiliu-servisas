<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\StandardPricingStrategy;
use App\Services\UrgentPricingStrategy;
use App\Services\WeekendPricingStrategy;

#[TestClass]
class PricingStrategyTest extends TestCase
{
    private float $basePrice = 100.00;

    #[Test]
    public function standard_pricing_returns_base_price(): void
    {
        // Arrange
        $strategy = new StandardPricingStrategy();

        // Act
        $price = $strategy->calculatePrice($this->basePrice);

        // Assert
        $this->assertEquals($this->basePrice, $price);
    }

    #[Test]
    public function urgent_pricing_adds_fifty_percent(): void
    {
        // Arrange
        $strategy = new UrgentPricingStrategy();

        // Act
        $price = $strategy->calculatePrice($this->basePrice);

        // Assert
        $this->assertEquals($this->basePrice * 1.5, $price);
    }

    #[Test]
    public function weekend_pricing_doubles_price(): void
    {
        // Arrange
        $strategy = new WeekendPricingStrategy();

        // Act
        $price = $strategy->calculatePrice($this->basePrice);

        // Assert
        $this->assertEquals($this->basePrice * 2, $price);
    }

    #[Test]
    #[DataProvider('priceDataProvider')]
    public function pricing_strategies_calculate_correctly(float $basePrice): void
    {
        $strategies = [
            new StandardPricingStrategy(),
            new UrgentPricingStrategy(),
            new WeekendPricingStrategy()
        ];

        foreach ($strategies as $strategy) {
            $calculatedPrice = $strategy->calculatePrice($basePrice);
            $this->assertIsFloat($calculatedPrice);
            $this->assertGreaterThan(0, $calculatedPrice);
        }
    }

    public static function priceDataProvider(): array
    {
        return [
            'low_price' => [50.00],
            'medium_price' => [100.00],
            'high_price' => [150.00],
        ];
    }
}