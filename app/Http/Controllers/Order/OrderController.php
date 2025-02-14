<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['vehicle.client', 'service'])->get();
        return Inertia::render('Orders/Index', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        $vehicles = Vehicle::with('client')->get();
        $services = Service::all();
        return Inertia::render('Orders/Create', [
            'vehicles' => $vehicles,
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
        $vehicles = Vehicle::with('client')->get();
        $services = Service::all();
        return Inertia::render('Orders/Edit', [
            'order' => $order,
            'vehicles' => $vehicles,
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