<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Inertia\Inertia;


class EmployeeController extends Controller
{
    public function index(Request $request)
{
    $employees = Employee::query()
        ->when($request->search, fn($q) =>
            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('employee_type', 'like', '%' . $request->search . '%')
              ->orWhere('phone', 'like', '%' . $request->search . '%')
        )
        ->paginate(10)
        ->withQueryString();

    return Inertia::render('Employee/Index', [
        'employees' => $employees,
        'filters' => $request->only('search')
    ]);
}

public function store(Request $request)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'employee_type' => 'required|string',
        'phone' => 'required|string|max:20',
        'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
    ]);

    if ($request->hasFile('file')) {
        $data['file_path'] = $request->file('file')->store('employee_files', 'public');
    }

    Employee::create($data);
    return back()->with('success', 'Employee added successfully.');
}

public function update(Request $request, Employee $employee)
{
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'employee_type' => 'required|string',
        'phone' => 'required|string|max:20',
        'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
    ]);

    if ($request->hasFile('file')) {
        $data['file_path'] = $request->file('file')->store('employee_files', 'public');
    }

    $employee->update($data);
    return back()->with('success', 'Employee updated successfully.');
}

public function destroy(Employee $employee)
{
    $employee->delete();
    return back()->with('success', 'Employee deleted.');
}

}
