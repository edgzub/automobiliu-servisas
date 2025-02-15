<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Service;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['vehicle.client', 'service'])->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'vehicle' => $order->vehicle,
                'service' => $order->service,
                'data' => $order->data,
                'statusas' => $order->statusas,
                'komentarai' => $order->komentarai,
                'kaina' => (float) $order->kaina,
            ];
        });
    
        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        // Pakeista, kad grąžintų clients vietoj vehicles
        $clients = Client::with('vehicles')->get();
        $services = Service::all()->map(function ($service) {
            return [
                'id' => $service->id,
                'pavadinimas' => $service->pavadinimas,
                'kaina' => (float) $service->kaina,
                'kategorija' => $service->kategorija
            ];
        });

        return Inertia::render('Orders/Create', [
            'clients' => $clients,
            'services' => $services
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_id' => 'required|exists:services,id',
            'data' => 'required|date',
            'statusas' => 'required|in:laukiama,vykdoma,atlikta,atsaukta',
            'komentarai' => 'nullable|string',
            'kaina' => 'required|numeric|min:0',
        ]);

        Order::create($validated);

        return redirect()->route('orders.index')
            ->with('message', 'Užsakymas sėkmingai sukurtas');
    }

    public function edit(Order $order)
    {
        $order->load(['vehicle.client', 'service']);
        
        $clients = Client::with('vehicles')->get();
        $services = Service::all()->map(function ($service) {
            return [
                'id' => $service->id,
                'pavadinimas' => $service->pavadinimas,
                'kaina' => (float) $service->kaina,
                'kategorija' => $service->kategorija
            ];
        });

        return Inertia::render('Orders/Edit', [
            'order' => [
                'id' => $order->id,
                'vehicle' => $order->vehicle,
                'service' => $order->service,
                'data' => $order->data,
                'statusas' => $order->statusas,
                'komentarai' => $order->komentarai,
                'kaina' => (float) $order->kaina,
                'vehicle_id' => $order->vehicle_id,
                'service_id' => $order->service_id,
            ],
            'clients' => $clients,
            'services' => $services
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'service_id' => 'required|exists:services,id',
            'data' => 'required|date',
            'statusas' => 'required|in:laukiama,vykdoma,atlikta,atsaukta',
            'komentarai' => 'nullable|string',
            'kaina' => 'required|numeric|min:0',
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')
            ->with('message', 'Užsakymas sėkmingai atnaujintas');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('orders.index')
            ->with('message', 'Užsakymas sėkmingai ištrintas');
    }
}