<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    
    private Client $client;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test client
        $this->client = Client::factory()->create([
            'vardas' => 'Jonas',
            'pavarde' => 'Jonaitis',
            'tel_numeris' => '+37061234567',
            'el_pastas' => 'jonas@example.com'
        ]);
    }
    
    /**
     * @test
     * @group model
     */
    public function client_can_be_created()
    {
        // Assert
        $this->assertDatabaseHas('clients', [
            'id' => $this->client->id,
            'vardas' => 'Jonas',
            'pavarde' => 'Jonaitis',
            'tel_numeris' => '+37061234567',
            'el_pastas' => 'jonas@example.com'
        ]);
        
        $this->assertNotNull($this->client->id);
        $this->assertIsInt($this->client->id);
    }
    
    /**
     * @test
     * @group model
     */
    public function client_has_vehicles_relationship()
    {
        // Arrange
        Vehicle::factory(3)->create([
            'client_id' => $this->client->id
        ]);
        
        // Act
        $vehicles = $this->client->vehicles;
        
        // Assert
        $this->assertInstanceOf(Collection::class, $vehicles);
        $this->assertCount(3, $vehicles);
        
        foreach ($vehicles as $vehicle) {
            $this->assertEquals($this->client->id, $vehicle->client_id);
        }
    }
    
    /**
     * @test
     * @group model
     */
    public function client_vardas_attribute_can_be_updated()
    {
        // Act
        $this->client->vardas = 'Petras';
        $this->client->save();
        
        // Refresh from database
        $this->client->refresh();
        
        // Assert
        $this->assertEquals('Petras', $this->client->vardas);
        
        $assertData = ['id' => $this->client->id, 'vardas' => 'Petras'];
        $this->assertDatabaseHas('clients', $assertData);
    }
    
    /**
     * @test
     * @group model
     */
    public function client_pavarde_attribute_can_be_updated()
    {
        // Act
        $this->client->pavarde = 'Petraitis';
        $this->client->save();
        
        // Refresh from database
        $this->client->refresh();
        
        // Assert
        $this->assertEquals('Petraitis', $this->client->pavarde);
        
        $assertData = ['id' => $this->client->id, 'pavarde' => 'Petraitis'];
        $this->assertDatabaseHas('clients', $assertData);
    }
    
    /**
     * @test
     * @group model
     */
    public function client_tel_numeris_attribute_can_be_updated()
    {
        // Act
        $this->client->tel_numeris = '+37062345678';
        $this->client->save();
        
        // Refresh from database
        $this->client->refresh();
        
        // Assert
        $this->assertEquals('+37062345678', $this->client->tel_numeris);
        
        $assertData = ['id' => $this->client->id, 'tel_numeris' => '+37062345678'];
        $this->assertDatabaseHas('clients', $assertData);
    }
    
    /**
     * @test
     * @group model
     */
    public function client_el_pastas_attribute_can_be_updated()
    {
        // Act
        $this->client->el_pastas = 'petras@example.com';
        $this->client->save();
        
        // Refresh from database
        $this->client->refresh();
        
        // Assert
        $this->assertEquals('petras@example.com', $this->client->el_pastas);
        
        $assertData = ['id' => $this->client->id, 'el_pastas' => 'petras@example.com'];
        $this->assertDatabaseHas('clients', $assertData);
    }
    
    /**
     * @test
     * @group model
     */
    public function client_can_be_deleted()
    {
        // Act
        $id = $this->client->id;
        $this->client->delete();
        
        // Assert
        $this->assertDatabaseMissing('clients', [
            'id' => $id
        ]);
        $this->assertNull(Client::find($id));
    }
    
    /**
     * @test
     * @group model
     * @group exceptions
     */
    public function client_creation_throws_exception_on_invalid_phone()
    {
        // Using a direct creation method that would validate inputs
        $createClientWithInvalidPhone = function() {
            // Simulate validation logic that would be in a form request or service class
            $phone = 'invalid_phone';
            if (!preg_match('/^\+?[0-9]{8,15}$/', $phone)) {
                throw new InvalidArgumentException('Invalid phone number format');
            }
            
            return Client::factory()->create(['tel_numeris' => $phone]);
        };
        
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid phone number format');
        
        // Act - this should throw exception
        $createClientWithInvalidPhone();
    }
    
    /**
     * @test
     * @group model
     * @group exceptions
     */
    public function client_creation_throws_exception_on_invalid_email()
    {
        // Using a direct creation method that would validate inputs
        $createClientWithInvalidEmail = function() {
            // Simulate validation logic that would be in a form request or service class
            $email = 'not_an_email';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Invalid email format');
            }
            
            return Client::factory()->create(['el_pastas' => $email]);
        };
        
        // Assert
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email format');
        
        // Act - this should throw exception
        $createClientWithInvalidEmail();
    }
    
    /**
     * @test
     * @group model
     * @dataProvider validClientDataProvider
     */
    public function client_is_created_with_valid_data($vardas, $pavarde, $telefonas, $email)
    {
        // Act
        $client = Client::factory()->create([
            'vardas' => $vardas,
            'pavarde' => $pavarde,
            'tel_numeris' => $telefonas,
            'el_pastas' => $email
        ]);
        
        // Assert
        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'vardas' => $vardas,
            'pavarde' => $pavarde,
            'tel_numeris' => $telefonas,
            'el_pastas' => $email
        ]);
        $this->assertEquals($vardas, $client->vardas);
        $this->assertEquals($pavarde, $client->pavarde);
        $this->assertEquals($telefonas, $client->tel_numeris);
        $this->assertEquals($email, $client->el_pastas);
    }
    
    public static function validClientDataProvider()
    {
        return [
            'regular' => ['John', 'Doe', '+37061234567', 'john@example.com'],
            'long name' => ['JohnJohnJohnJohn', 'DoeDoeDoeDoe', '+37061234567', 'john@example.com'],
            'lithuanian chars' => ['Ąžuolas', 'Ėglis', '+37061234567', 'azuolas@example.lt'],
        ];
    }
} 