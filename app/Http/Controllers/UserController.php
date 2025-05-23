<?php


namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Http\Requests\UserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserController extends Controller
{
    protected $users;
  public function __construct(protected UserService $user) {
    $this->users = $user;
  }

    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        // dd($permissions);
        return Inertia::render('Dashboard/Users/Index', [
            'users' => $this->users->with(['roles:name', 'permissions:name']),
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }



public function store(UserRequest $request)
{
    $validated = $request->validated();
    $validated['password'] = bcrypt($validated['password']);

    $user = $this->users->createUser($validated);
    if ($request->filled('role')) {
        $user->syncRoles([$request->role]); 
    }
    if ($request->filled('permissions')) {
        $user->syncPermissions($request->permissions);
    }
    return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
}


public function update(UserRequest $request, User $user)
{
    $validated = $request->validated();
    if (!empty($validated['password'])) {
        $validated['password'] = bcrypt($validated['password']);
    } else {
        unset($validated['password']);
    }
    $this->users->updateUser($user, $validated);
    if ($request->filled('role')) {
        $user->syncRoles([$request->role]);
    }
    if ($request->filled('permissions')) {
        $user->syncPermissions($request->permissions);
    }
    return redirect()->route('admin.users.index')->with('success', 'User updated');
}


    public function destroy(User $user)
    {
        $this->users->deleteUser($user);
        return redirect()->route('admin.users.index')->with('success', 'User deleted');
    }
}
