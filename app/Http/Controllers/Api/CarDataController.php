<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Client;
use App\Services\VehicleDataAdapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class CarDataController extends Controller
{
    // Grąžina visas automobilių markes
    public function makes()
    {
        try {
            $response = Http::get('https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json');
            
            if ($response->successful()) {
                $data = $response->json();
                $results = $data['Results'];
                
                // Filtravimo variantai:
                
                // A: Grąžinti tik populiarius gamintojus (pridėkite daugiau pagal poreikį)
                $popularMakes = ['FORD', 'CHEVROLET', 'TOYOTA', 'HONDA', 'NISSAN', 'BMW', 'MERCEDES-BENZ', 
                                'AUDI', 'VOLKSWAGEN', 'SUBARU', 'MAZDA', 'DODGE', 'JEEP', 'KIA', 'HYUNDAI', 
                                'LEXUS', 'ACURA', 'VOLVO', 'CADILLAC', 'CHRYSLER', 'INFINITI', 'MITSUBISHI', 
                                'BUICK', 'GMC', 'LINCOLN', 'PORSCHE', 'LAND ROVER', 'MINI', 'FIAT', 'TESLA'];
                
                $filteredResults = array_filter($results, function($item) use ($popularMakes) {
                    return in_array(strtoupper($item['Make_Name']), $popularMakes);
                });
                
                // B: Arba imkite tik pirmus 50 (galite pakeisti skaičių)
                // $filteredResults = array_slice($results, 0, 50);
                
                // Rūšiuokite pagal pavadinimą
                usort($filteredResults, function($a, $b) {
                    return strcmp($a['Make_Name'], $b['Make_Name']);
                });
                
                return response()->json(array_values($filteredResults));
            }
            
            return response()->json(['error' => 'Nepavyko gauti automobilių gamintojų'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'API užklausos klaida: ' . $e->getMessage()], 500);
        }
    }
    
    // Grąžina visus konkrečios markės modelius
    public function models($make)
    {
        try {
            $response = Http::get("https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformake/{$make}?format=json");
            
            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data['Results']);
            }
            
            return response()->json(['error' => 'Nepavyko gauti modelių duomenų'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'API užklausos klaida: ' . $e->getMessage()], 500);
        }
    }
    
    // Grąžina metus (nuo 1995 iki dabar)
    public function years()
    {
        $years = [];
        $currentYear = (int) date('Y');
        
        for ($i = $currentYear; $i >= $currentYear - 30; $i--) {
            $years[] = ['year' => $i];
        }
        
        return response()->json($years);
    }
    
    // Dekodavimas pagal VIN
    public function decodeVin($vin)
    {
        try {
            $response = Http::get("https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/{$vin}?format=json");
            
            if ($response->successful()) {
                $data = $response->json();
                return response()->json($data['Results']);
            }
            
            return response()->json(['error' => 'Nepavyko dekoduoti VIN numerio'], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'API užklausos klaida: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Importuoja transporto priemones iš NHTSA API
     */
    public function importVehiclesFromApi(Request $request)
    {
        try {
            $client_id = $request->input('client_id');
            
            if (!$client_id) {
                return response()->json(['error' => 'Nepateiktas kliento ID'], 400);
            }
            
            // Tikriname, ar klientas egzistuoja
            $client = \App\Models\Client::find($client_id);
            if (!$client) {
                return response()->json(['error' => 'Nurodytas klientas neegzistuoja'], 404);
            }
            
            // Gaukime populiarias markes
            $makesResponse = $this->makes();
            $makes = json_decode($makesResponse->getContent(), true);
            
            if (!isset($makes) || !is_array($makes)) {
                return response()->json(['error' => 'Nepavyko gauti markių iš API'], 500);
            }
            
            $imported = 0;
            $errors = [];
            
            // Importuojame po 1 automobilį kiekvienai markei
            foreach (array_slice($makes, 0, 5) as $make) {
                try {
                    // Gaukime modelius šiai markei
                    $modelsResponse = $this->models($make['Make_Name']);
                    $models = json_decode($modelsResponse->getContent(), true);
                    
                    if (empty($models)) {
                        continue;
                    }
                    
                    // Pasirinkime pirmą modelį
                    $model = $models[0];
                    
                    // Sugeneruokime atsitiktinį VIN
                    $vin = $this->generateRandomVin();
                    
                    // Gauname metus
                    $yearsResponse = $this->years();
                    $years = json_decode($yearsResponse->getContent(), true);
                    $year = $years[array_rand($years)]['year'];
                    
                    // Sukuriame duomenis
                    $vehicleData = [
                        'registration_number' => $this->generateRandomPlate(),
                        'vin' => $vin,
                        'make' => $make['Make_Name'],
                        'model' => $model['Model_Name'],
                        'year' => $year
                    ];
                    
                    // Adaptuojame duomenis į lokalų formatą
                    $adapter = new \App\Services\VehicleDataAdapter();
                    $adaptedData = $adapter->adaptFromExternalApi($vehicleData);
                    $adaptedData['client_id'] = $client_id;
                    
                    // Tikriname, ar automobilis jau egzistuoja
                    $existingVehicle = \App\Models\Vehicle::where('vin_kodas', $adaptedData['vin_kodas'])->first();
                    
                    if ($existingVehicle) {
                        // Atnaujiname egzistuojantį automobilį
                        $existingVehicle->update($adaptedData);
                    } else {
                        // Kuriame naują automobilį
                        \App\Models\Vehicle::create($adaptedData);
                    }
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Klaida importuojant markę {$make['Make_Name']}: " . $e->getMessage();
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sėkmingai importuoti {$imported} automobiliai",
                'errors' => $errors
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Importavimo klaida: ' . $e->getMessage()], 500);
        }
    }
    
    /**
     * Sugeneruoja atsitiktinį valstybinį numerį
     */
    private function generateRandomPlate()
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $plate = '';
        
        // 3 raidės
        for ($i = 0; $i < 3; $i++) {
            $plate .= $letters[rand(0, strlen($letters) - 1)];
        }
        
        // 3 skaičiai
        $plate .= rand(100, 999);
        
        return $plate;
    }
    
    /**
     * Sugeneruoja atsitiktinį VIN kodą
     */
    private function generateRandomVin()
    {
        $characters = 'ABCDEFGHJKLMNPRSTUVWXYZ0123456789'; // VIN neturi I, O, Q
        $vin = '';
        
        for ($i = 0; $i < 17; $i++) {
            $vin .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        return $vin;
    }
}