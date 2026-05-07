<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        \Log::info('Admin login attempt', ['email' => $request->email]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            \Log::info('Login successful', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role ?? 'null'
            ]);

            // Cek role
            if (Auth::user()->role === 'admin') {
                \Log::info('Redirecting to admin dashboard');
                return redirect()->route('admin.dashboard');
            }

            // Jika bukan admin, logout dan redirect back
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'Anda tidak memiliki akses admin.',
            ]);
        }

        \Log::warning('Login failed', ['email' => $request->email]);
        throw ValidationException::withMessages([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/admin/login');
    }
}