<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}