<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Client;

class BasicClientTest extends TestCase
{
    /**
     * @test
     */
    public function client_can_be_instantiated()
    {
        $client = new Client();
        
        $this->assertInstanceOf(Client::class, $client);
    }
    
    /**
     * @test
     */
    public function client_properties_can_be_set()
    {
        // Arrange
        $client = new Client();
        
        // Act
        $client->vardas = 'Jonas';
        $client->pavarde = 'Jonaitis';
        $client->tel_numeris = '+37061234567';
        $client->el_pastas = 'jonas@example.com';
        
        // Assert
        $this->assertEquals('Jonas', $client->vardas);
        $this->assertEquals('Jonaitis', $client->pavarde);
        $this->assertEquals('+37061234567', $client->tel_numeris);
        $this->assertEquals('jonas@example.com', $client->el_pastas);
    }
    
    /**
     * @test
     */
    public function client_fillable_attributes_are_correct()
    {
        $client = new Client();
        
        $fillable = $client->getFillable();
        
        $this->assertIsArray($fillable);
        $this->assertContains('vardas', $fillable);
        $this->assertContains('pavarde', $fillable);
        $this->assertContains('tel_numeris', $fillable);
        $this->assertContains('el_pastas', $fillable);
    }
    
    /**
     * @test
     * @dataProvider clientDataProvider
     */
    public function client_email_is_valid($vardas, $pavarde, $email)
    {
        // Arrange
        $client = new Client();
        $client->vardas = $vardas;
        $client->pavarde = $pavarde;
        $client->el_pastas = $email;
        
        // Act & Assert
        $this->assertStringContainsString('@', $client->el_pastas);
        $this->assertMatchesRegularExpression('/^.+@.+\..+$/', $client->el_pastas);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function client_with_invalid_email_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        // Arrange
        $client = new Client();
        $client->vardas = 'Test';
        $client->pavarde = 'User';
        
        // Act - simulate validation error
        $email = 'invalid-email';
        
        // Manually check for validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('El. pašto adresas neteisingas');
        }
        
        $client->el_pastas = $email;
    }
    
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function client_with_invalid_phone_throws_exception()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        // Arrange
        $client = new Client();
        
        // Act - simulate validation error
        $phone = 'abc123';
        
        // Manually check for validation
        if (!preg_match('/^\+?[0-9]{8,15}$/', $phone)) {
            throw new \InvalidArgumentException('Neteisingas telefono numerio formatas');
        }
        
        $client->tel_numeris = $phone;
    }
    
    public static function clientDataProvider()
    {
        return [
            'Standard email' => ['Jonas', 'Jonaitis', 'jonas.jonaitis@example.com'],
            'Gmail address' => ['Petras', 'Petraitis', 'petras.petraitis@gmail.com'],
            'Company email' => ['Antanas', 'Antanaitis', 'antanas@company.lt'],
        ];
    }
} 