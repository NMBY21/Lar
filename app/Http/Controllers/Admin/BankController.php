<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BankController extends Controller
{
    public function index(Request $request)
    {
        $banks = Bank::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Bank/Index', [
            'banks' => $banks,
            'filters' => $request->only('search'),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Bank::create($request->only('name'));
        return back()->with('success', 'Bank added successfully');
    }

    public function show(Bank $bank, Request $request)
{
    $query = $bank->accounts();

    if ($search = $request->get('search')) {
        $query->where('account', 'like', "%{$search}%");
    }

    $accounts = $query->paginate(10)->withQueryString();

    return Inertia::render('Bank/Show', [
        'bank' => $bank,
        'accounts' => $accounts,
    ]);
}

}
