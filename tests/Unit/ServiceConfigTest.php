<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\ServiceConfig;

#[TestClass]
class ServiceConfigTest extends TestCase
{
    private ServiceConfig $config;

    #[SetUp]
    protected function setUp(): void
    {
        parent::setUp();
        $this->config = ServiceConfig::getInstance();
    }

    #[Test]
    public function singleton_returns_same_instance(): void
    {
        $instance1 = ServiceConfig::getInstance();
        $instance2 = ServiceConfig::getInstance();

        $this->assertSame($instance1, $instance2);
    }

    #[Test]
    public function get_statuses_returns_array(): void
    {
        $statuses = $this->config->getStatuses();

        $this->assertIsArray($statuses);
        $this->assertContains('laukiama', $statuses);
        $this->assertContains('vykdoma', $statuses);
        $this->assertContains('atlikta', $statuses);
        $this->assertContains('atsaukta', $statuses);
    }

    #[Test]
    public function calculates_price_with_vat(): void
    {
        $price = 100.00;
        $expectedVatRate = 0.21;

        $priceWithVat = $this->config->calculatePriceWithVAT($price);

        $this->assertEquals($price * (1 + $expectedVatRate), $priceWithVat);
    }

    #[Test]
    public function get_returns_null_for_invalid_key(): void
    {
        $value = $this->config->get('invalid_key');

        $this->assertNull($value);
    }
}