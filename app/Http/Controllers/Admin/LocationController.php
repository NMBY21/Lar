<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $locations = Location::query()
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%')
            )
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Location/Index', [
            'locations' => $locations,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Location::create($request->only('name'));
        return back()->with('success', 'Location created successfully.');
    }

    public function update(Request $request, Location $location)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $location->update($request->only('name'));
        return back()->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return back()->with('success', 'Location deleted successfully.');
    }
}
