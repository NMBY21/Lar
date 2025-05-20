<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\SystemUserRequest;
use Spatie\Permission\Models\Role;

class SystemUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->whereHas('roles');

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            });
        }

        $users = $query->with('roles')->latest()->paginate(5)->withQueryString();
        $roles = Role::all();

        return Inertia::render('Admin/SystemUser/Index', [
            'users' => $users,
            'roles' => $roles,
            'filters' => $request->only('search')
        ]);
    }

    public function store(SystemUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
        ]);

        // $user->syncRoles([$request->role_id]);
        $role = Role::findOrFail($request->role_id);
        $user->assignRole($role);

        return redirect()->back()->with('success', 'System user created');

    
}
    public function update(Request $request, User $user)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'phone' => 'nullable|string|max:20',
        'role_id' => 'required|exists:roles,id',
    ]);

    $user->update($validated);

    // Optional: re-assign role
    $user->roles()->sync([$request->role_id]);

    return redirect()->back();
}

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->back()->with('success', 'System user deleted');
    }
}
