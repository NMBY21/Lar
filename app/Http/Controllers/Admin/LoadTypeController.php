<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoadType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoadTypeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $loadTypes = LoadType::when($search, fn($q) =>
            $q->where('name', 'like', "%{$search}%")
        )->paginate(10)->withQueryString();

        return Inertia::render('LoadType/Index', [
            'loadTypes' => $loadTypes,
            'filters' => $request->only('search')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        LoadType::create($request->only('name'));

        return back()->with('success', 'Load type added.');
    }

    public function update(Request $request, LoadType $loadType)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $loadType->update($request->only('name'));

        return back()->with('success', 'Load type updated.');
    }

    public function destroy(LoadType $loadType)
    {
        $loadType->delete();

        return back()->with('success', 'Load type deleted.');
    }
}
