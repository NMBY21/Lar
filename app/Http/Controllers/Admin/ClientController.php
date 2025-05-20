<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $clients = Client::when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return Inertia::render('Admin/Client/Index', [
            'clients' => $clients,
            'filters' => [
                'search' => $search
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        Client::create($validated);

        return redirect()->route('admin.client.index')->with('success', 'Client created successfully.');
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $client->update($validated);

        return redirect()->route('admin.client.index')->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('admin.client.index')->with('success', 'Client deleted successfully.');
    }
}
