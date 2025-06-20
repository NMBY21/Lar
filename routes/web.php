<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\SystemUserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ExpenseTypeController;
use App\Http\Controllers\Admin\ExpenseController;



Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
});

// client
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('client', [ClientController::class, 'index'])->name('admin.client.index');
    Route::post('client', [ClientController::class, 'store'])->name('admin.client.store');
    Route::put('client/{client}', [ClientController::class, 'update'])->name('admin.client.update');
    Route::delete('client/{client}', [ClientController::class, 'destroy'])->name('admin.client.destroy');
});

// system user
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('system-users', [SystemUserController::class, 'index'])->name('admin.system-users.index');
    Route::post('system-users', [SystemUserController::class, 'store'])->name('admin.system-users.store');
    Route::put('system-users/{user}', [SystemUserController::class, 'update'])->name('admin.system-users.update');
    Route::delete('system-users/{user}', [SystemUserController::class, 'destroy'])->name('admin.system-users.destroy');
});

//role and permission
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::get('roles', [RoleController::class, 'index'])->name('admin.roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('admin.roles.store');
    // Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    Route::post('roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('admin.roles.permissions');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
    Route::delete('roles/{role}action: ', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
    // Route::delete('/admin/system-users/{user}', [SystemUserController::class, 'destroy'])->name('admin.system-users.destroy');

});
// permission
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index']);
    Route::post('/roles/{role}/assign-permissions', [RoleController::class, 'assignPermissions'])
        ->name('admin.roles.assign-permissions');
});

//bank
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('banks', BankController::class)
        ->except(['show'])
        ->names('admin.banks');    Route::get('banks/{bank}', [BankController::class, 'show'])->name('admin.banks.show');
    Route::put('/accounts/{account}', [AccountController::class, 'update'])->name('admin.accounts.update');
    Route::post('/accounts', [AccountController::class, 'store'])->name('admin.accounts.store');
    Route::delete('/accounts/{account}', [AccountController::class, 'destroy'])->name('admin.accounts.destroy');
});
// load type
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('load-types', \App\Http\Controllers\Admin\LoadTypeController::class)
        ->only(['index', 'store', 'update', 'destroy']);
});

//Location
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('locations', LocationController::class)->except(['create', 'edit', 'show']);
});

//Employee
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::resource('employees', \App\Http\Controllers\Admin\EmployeeController::class)
        ->except(['create', 'edit', 'show']);
    Route::put('admin/employees/{employee}', [EmployeeController::class, 'update']);
});

// Expense type
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('expense-types', \App\Http\Controllers\Admin\ExpenseTypeController::class)->except(['create', 'edit', 'show']);
    Route::get('/expense-types/filter', function (Request $request) {
        $category = $request->query('category');

        $types = \App\Models\ExpenseType::where('category', $category)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($types);
    })->name('expense-types.filter');
    Route::get('/expense-types/by-category/{category}', [ExpenseTypeController::class, 'getByCategory']);
});

//Expenses
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::resource('expenses', \App\Http\Controllers\Admin\ExpenseController::class);
    Route::get('/api/expense-dependencies', function () {
        return response()->json([
            'banks' => \App\Models\Bank::all(),
            'accounts' => \App\Models\Account::all(),
            'expense_types' => \App\Models\ExpenseType::all(),
        ]);
    });
    Route::get('/expense-types/by-category/{category}', [ExpenseTypeController::class, 'getByCategory']);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
