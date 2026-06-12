<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()
                ->with('error', 'Email atau password salah.')
                ->withInput();
        }

        $request->session()->regenerate();
        return redirect('/redirect');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }


    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|numeric|digits_between:10,15',
            'email' => 'required|email|unique:user,email', 
            'password' => 'required|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'phone.required' => 'No HP wajib diisi.',
            'phone.numeric' => 'No HP harus berupa angka.',
            'phone.digits_between' => 'No HP harus 10-15 digit.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $role = Role::where('nama_role', 'anggota')->first();
        if (!$role) {
            return back()->with('error', 'Role anggota tidak ditemukan.');
        }
        User::create([
            'nama_user' => $request->name,
            'alamat' => $request->address,
            'no_hp' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_role' => $role->id_role,
        ]);
        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan masuk menggunakan akun Anda.');
    }
}