<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Menampilkan halaman login
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required',
        ]);

        // Try login with email
        if (filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        } else {
            // Try login with PERNR
            $user = User::where('pernr', $request->email)->first();
            
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user, $request->filled('remember'));
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'));
            }
        }

        return back()->withErrors([
            'email' => 'Email/PERNR atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
