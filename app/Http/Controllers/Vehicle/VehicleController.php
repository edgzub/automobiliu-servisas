<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::with(['client', 'orders'])->get();
        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles
        ]);
    }

    public function create()
    {
        $clients = Client::all();
        return Inertia::render('Vehicles/Create', [
            'clients' => $clients
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'marke' => 'required|string|max:255',
            'modelis' => 'required|string|max:255',
            'metai' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'valstybinis_numeris' => 'required|string|unique:vehicles',
            'vin_kodas' => 'required|string|unique:vehicles',
        ]);

        Vehicle::create($validated);

        return redirect()->route('vehicles.index')
            ->with('message', 'Automobilis sėkmingai pridėtas');
    }

    public function edit(Vehicle $vehicle)
    {
        $clients = Client::all();
        return Inertia::render('Vehicles/Edit', [
            'vehicle' => $vehicle,
            'clients' => $clients
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'marke' => 'required|string|max:255',
            'modelis' => 'required|string|max:255',
            'metai' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'valstybinis_numeris' => 'required|string|unique:vehicles,valstybinis_numeris,' . $vehicle->id,
            'vin_kodas' => 'required|string|unique:vehicles,vin_kodas,' . $vehicle->id,
        ]);

        $vehicle->update($validated);

        return redirect()->route('vehicles.index')
            ->with('message', 'Automobilis sėkmingai atnaujintas');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')
            ->with('message', 'Automobilis sėkmingai ištrintas');
    }

    public function importFromApi(Request $request)
    {
        // Naudojame CarDataController kad importuotume duomenis
        $controller = new \App\Http\Controllers\Api\CarDataController();
        $response = $controller->importVehiclesFromApi($request);
        
        // Konvertuojame JSON atsakymą į masyvą
        $data = json_decode($response->getContent(), true);
        
        if (!empty($data['errors'])) {
            return redirect()->route('vehicles.index')
                ->with('error', implode(', ', $data['errors']));
        }
        
        return redirect()->route('vehicles.index')
            ->with('message', $data['message'] ?? 'Automobiliai importuoti sėkmingai');
    }
}