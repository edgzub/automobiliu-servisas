<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index()
    {
        return Inertia::render('Clients/Index', [
            'clients' => Client::with('vehicles')->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Clients/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vardas' => 'required|string|max:255',
            'pavarde' => 'required|string|max:255',
            'tel_numeris' => 'required|string|max:20',
            'el_pastas' => 'required|email|unique:clients',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('message', 'Klientas sėkmingai sukurtas');
    }

    public function edit(Client $client)
    {
        return Inertia::render('Clients/Edit', [
            'client' => $client
        ]);
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'vardas' => 'required|string|max:255',
            'pavarde' => 'required|string|max:255',
            'tel_numeris' => 'required|string|max:20',
            'el_pastas' => 'required|email|unique:clients,el_pastas,' . $client->id,
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('message', 'Klientas sėkmingai atnaujintas');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
            ->with('message', 'Klientas sėkmingai ištrintas');
    }
}