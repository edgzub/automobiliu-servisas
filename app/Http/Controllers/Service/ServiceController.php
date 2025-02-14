<?php

namespace App\Http\Controllers\Service;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('orders')->get();
        return Inertia::render('Services/Index', [
            'services' => $services
        ]);
    }

    public function create()
    {
        return Inertia::render('Services/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pavadinimas' => 'required|string|max:255',
            'aprasymas' => 'required|string',
            'kaina' => 'required|numeric|min:0',
            'trukme_valandomis' => 'required|numeric|min:0',
            'kategorija' => 'required|string|max:255',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('message', 'Paslauga sėkmingai sukurta');
    }

    public function edit(Service $service)
    {
        return Inertia::render('Services/Edit', [
            'service' => $service
        ]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'pavadinimas' => 'required|string|max:255',
            'aprasymas' => 'required|string',
            'kaina' => 'required|numeric|min:0',
            'trukme_valandomis' => 'required|numeric|min:0',
            'kategorija' => 'required|string|max:255',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('message', 'Paslauga sėkmingai atnaujinta');
    }

    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('message', 'Paslauga sėkmingai ištrinta');
    }
}