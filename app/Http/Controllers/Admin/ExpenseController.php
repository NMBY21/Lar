<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use Inertia\Inertia;


class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::with('expenseType', 'fromBank', 'fromAccount', 'toBank');


        if ($request->search) {
            $query->whereHas('expenseType', fn($q) =>
                $q->where('name', 'like', '%' . $request->search . '%'));
        }

        if ($request->from && $request->to) {
            $query->whereBetween('date', [$request->from, $request->to]);
        }


        return Inertia::render('Expenses/Index', [
            'expenses' => $query->latest()->paginate(10),
            'filters' => $request->only('search', 'from', 'to'),
            'expenseTypes' => \App\Models\ExpenseType::all(),
            'banks' => \App\Models\Bank::all(),
            'accounts' => \App\Models\Account::all(),
            
        ]);

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'from_bank_id' => 'nullable|exists:banks,id',
            'from_account_id' => 'nullable|exists:accounts,id',
            'to_bank_id' => 'nullable|exists:banks,id',
            'to_account' => 'nullable|string',
            'payment_type' => 'required|in:cash,transfer,check',
            'file' => 'nullable|file|mimes:pdf,jpg,png,doc,docx',
            'remark' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('expenses', 'public');
        }

        Expense::create($data);

        return redirect()->back()->with('success', 'Expense added successfully!');
    }
}
