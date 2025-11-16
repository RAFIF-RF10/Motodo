<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleName = optional($user->role)->name;

            return redirect()->route('dashboard')
                ->with('success', 'Selamat datang' . ($roleName === 'Admin' ? ', Admin ' : ', ') . $user->name . '!');
        }

        return back()
            ->withInput($request->only('email'))
            ->with('error', 'Email atau password salah!');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:Admin,User'
        ]);

        $data['password'] = Hash::make($data['password']);
        $role = Role::where('name', $data['role'])->first();
        $data['role_id'] = $role ? $role->id : null;
        unset($data['role']);

        $user = User::create($data);
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Registrasi berhasil!');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil!');
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }
}
