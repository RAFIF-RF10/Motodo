<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Admin: Lihat semua user dengan role
    public function index()
    {
        $users = User::with('role')->whereHas('role', function ($query) {
            $query->where('name', 'User');
        })->get();
        $roles = Role::all();
        return view('teacher.settings.user.index', compact('users', 'roles'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user = User::findOrFail($id);
        $user->role_id = $request->role_id;
        $user->save();

        return response()->json(['message' => 'Role updated successfully', 'user' => $user]);
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user(); 

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user]);
    }
    public function create()
{
    $roles = Role::all();
    return view('teacher.settings.user.create', compact('roles'));
}

// Simpan user baru
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6|confirmed',
        'role_id' => 'required|exists:roles,id',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role_id' => $request->role_id,
    ]);

    return redirect()->route('teacher.settings.users.index')->with('success', 'User berhasil ditambahkan!');
}

// Form edit user
public function edit(User $user)
{
    $roles = Role::all();
    return view('teacher.settings.user.edit', compact('user', 'roles'));
}

// Update user
public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6|confirmed',
        'role_id' => 'required|exists:roles,id',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }
    $user->role_id = $request->role_id;
    $user->save();

    return redirect()->route('teacher.settings.users.index')->with('success', 'User berhasil diupdate!');
}

// Delete user
public function destroy(User $user)
{
    $user->delete();
    return redirect()->route('teacher.settings.users.index')->with('success', 'User berhasil dihapus!');
}
}
