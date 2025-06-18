<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseType;
use Inertia\Inertia;


class ExpenseTypeController extends Controller
{
    public function index(Request $request)
{
    $query = ExpenseType::query();

    if ($request->search) {
        $query->where('name', 'like', "%{$request->search}%");
    }

    return Inertia::render('ExpenseTypes/Index', [
        'expenseTypes' => $query->latest()->paginate(10)->withQueryString(),
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'categories' => 'required|array|min:1'
    ]);

    ExpenseType::create([
        'name' => $request->name,
        'categories' => $request->categories,
    ]);
}

public function update(Request $request, ExpenseType $expenseType)
{
    $request->validate([
        'name' => 'required',
        'categories' => 'required|array|min:1'
    ]);

    $expenseType->update([
        'name' => $request->name,
        'categories' => $request->categories,
    ]);
}

public function destroy(ExpenseType $expenseType)
{
    $expenseType->delete();
}

public function getByCategory($category)
{
    // Make sure `categories` is cast as array in the model
    $expenseTypes = ExpenseType::whereJsonContains('categories', $category)
        ->select('id', 'name')
        ->get();

    return response()->json($expenseTypes);
}

}
