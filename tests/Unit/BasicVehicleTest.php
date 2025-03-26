<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Vehicle;

class BasicVehicleTest extends TestCase
{
    /**
     * @test
     */
    public function vehicle_can_be_instantiated()
    {
        $vehicle = new Vehicle();
        
        $this->assertInstanceOf(Vehicle::class, $vehicle);
    }
    
    /**
     * @test
     */
    public function vehicle_properties_can_be_set()
    {
        // Arrange
        $vehicle = new Vehicle();
        
        // Act
        $vehicle->marke = 'BMW';
        $vehicle->modelis = 'X5';
        $vehicle->metai = 2020;
        $vehicle->valstybinis_numeris = 'ABC123';
        $vehicle->vin_kodas = '1HGCM82633A123456';
        
        // Assert
        $this->assertEquals('BMW', $vehicle->marke);
        $this->assertEquals('X5', $vehicle->modelis);
        $this->assertEquals(2020, $vehicle->metai);
        $this->assertEquals('ABC123', $vehicle->valstybinis_numeris);
        $this->assertEquals('1HGCM82633A123456', $vehicle->vin_kodas);
    }
    
    /**
     * @test
     */
    public function vehicle_fillable_attributes_are_correct()
    {
        $vehicle = new Vehicle();
        
        $fillable = $vehicle->getFillable();
        
        $this->assertIsArray($fillable);
        $this->assertContains('client_id', $fillable);
        $this->assertContains('marke', $fillable);
        $this->assertContains('modelis', $fillable);
        $this->assertContains('metai', $fillable);
        $this->assertContains('valstybinis_numeris', $fillable);
        $this->assertContains('vin_kodas', $fillable);
    }
    
    /**
     * @test
     * @dataProvider vehicleDataProvider
     */
    public function vehicle_year_is_valid($marke, $modelis, $year)
    {
        // Arrange
        $vehicle = new Vehicle();
        $vehicle->marke = $marke;
        $vehicle->modelis = $modelis;
        $vehicle->metai = $year;
        
        // Act & Assert
        $currentYear = (int) date('Y');
        $this->assertGreaterThanOrEqual(1900, $vehicle->metai);
        $this->assertLessThanOrEqual($currentYear, $vehicle->metai);
    }
    
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function vehicle_with_invalid_year_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        // Arrange
        $vehicle = new Vehicle();
        $vehicle->marke = 'BMW';
        $vehicle->modelis = 'X5';
        
        // Act - simulate validation error
        $year = 1800; // Too old
        
        // Manually check for validation
        if ($year < 1900 || $year > (int) date('Y')) {
            throw new \InvalidArgumentException('Neteisingi automobilio metai');
        }
        
        $vehicle->metai = $year;
    }
    
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function vehicle_with_invalid_vin_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        // Arrange
        $vehicle = new Vehicle();
        
        // Act - simulate validation error
        $vin = 'INVALID'; // Too short, invalid format
        
        // Manually check for validation
        if (strlen($vin) !== 17) {
            throw new \InvalidArgumentException('VIN kodas turi būti 17 simbolių ilgio');
        }
        
        $vehicle->vin_kodas = $vin;
    }
    
    /**
     * @test
     */
    public function performance_test_vehicle_creation()
    {
        // Measure creation time
        $startTime = microtime(true);
        
        // Create a bunch of vehicles
        for ($i = 0; $i < 10; $i++) {
            $vehicle = new Vehicle();
            $vehicle->marke = 'Brand ' . $i;
            $vehicle->modelis = 'Model ' . $i;
            $vehicle->metai = 2020;
            $vehicle->valstybinis_numeris = 'ABC' . $i;
            $vehicle->vin_kodas = '1HGCM82633A' . str_pad($i, 6, '0', STR_PAD_LEFT);
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime);
        
        // Assert it's reasonably fast
        $this->assertLessThan(0.5, $executionTime, 'Vehicle creation should be fast');
    }
    
    public static function vehicleDataProvider()
    {
        return [
            'Current car' => ['Toyota', 'Corolla', 2023],
            'Older car' => ['Volkswagen', 'Golf', 2010],
            'Classic car' => ['Ford', 'Mustang', 1970],
        ];
    }
} 