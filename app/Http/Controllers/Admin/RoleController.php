<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::query()
            ->when($search, fn($query) =>
                $query->where('name', 'like', "%{$search}%")
            )
            ->with('permissions')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Role/Index', [
            'roles' => $roles,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|unique:roles,name'
    ]);

    Role::create($validated);

    return back()->with('success', 'Role created successfully.');
}


    
    public function assignPermissions(Request $request, Role $role)
    {
        $validated = $request->validate([
            'permissions' => 'array|required',
            'permissions.*' => 'array',
        ]);

        $flatPermissions = [];

        foreach ($validated['permissions'] as $permissionId => $types) {
            foreach ($types as $type) {
                $flatPermissions[] = strtolower($type) . ' ' . strtolower($permissionId);
            }
        }

        // Assuming permissions are named like: "show users", "add books"
        $allPermissions = Permission::whereIn('name', $flatPermissions)->get();

        $role->syncPermissions($allPermissions);

        return back()->with('success', 'Permissions updated.');
    }
    public function update(Request $request, Role $role)
{
    $validated = $request->validate([
        'name' => 'required|string|unique:roles,name,' . $role->id
    ]);

    $role->update($validated);

    return back()->with('success', 'Role updated successfully.');
}

public function destroy(Role $role)
{
    $role->delete();

    return back()->with('success', 'Role deleted.');
}
public function allPermissions()
{
    return Permission::all();
}

}
