<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Paprastas metodas rolės patikrinimui
    protected function authorizeAdmin()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Neturi prieigos');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $this->authorizeAdmin();

        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'Vartotojas sukurtas sėkmingai.');
    }

    public function edit(User $user)
    {
        $this->authorizeAdmin();

        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|exists:roles,name',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        $user->syncRoles([$request->role]);

        return redirect()->route('users.index')->with('success', 'Vartotojas atnaujintas sėkmingai.');
    }

    public function destroy(User $user)
    {
        $this->authorizeAdmin();

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Vartotojas ištrintas.');
    }
}
