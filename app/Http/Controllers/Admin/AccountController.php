<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'bank_id' => 'required|exists:banks,id',
            'account' => 'required|string|max:255',
        ]);

        Account::create([
            'bank_id' => $request->bank_id,
            'account' => $request->account,
        ]);

        return back()->with('success', 'Account added successfully.');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return back()->with('success', 'Account deleted.');
    }
    public function update(Request $request, Account $account)
{
    $request->validate([
        'account' => 'required|string|max:255',
    ]);

    $account->update([
        'account' => $request->account,
    ]);

    return back()->with('success', 'Account updated successfully.');
}

}
