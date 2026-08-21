<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // --- User Auth ---
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            \App\Services\AuditLogger::log('user_login', 'authentication', 'success', 'User logged in successfully', [
                'user_id' => (string) Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role
            ]);
            
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('user.dashboard');
        }

        \App\Services\AuditLogger::log('login_failed', 'authentication', 'failed', 'Login attempt failed', [
            'attempted_email' => $request->email,
            'reason' => 'Invalid credentials'
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        \App\Services\AuditLogger::log('user_registered', 'authentication', 'success', 'New user registered successfully', [
            'registered_user_id' => (string) $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]);

        return redirect()->route('user.dashboard');
    }

    // --- Admin Auth ---
    public function showAdminLogin()
    {
        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role === 'admin') {
                \App\Services\AuditLogger::log('admin_login', 'authentication', 'success', 'Admin logged in successfully', [
                    'admin_id' => (string) Auth::id(),
                    'email' => Auth::user()->email,
                    'role' => Auth::user()->role
                ]);
                return redirect()->route('admin.dashboard');
            } else {
                $email = Auth::user()->email;
                Auth::logout();
                \App\Services\AuditLogger::log('login_failed', 'authentication', 'failed', 'Login attempt failed', [
                    'attempted_email' => $email,
                    'reason' => 'Unauthorized role attempt'
                ]);
                return back()->withErrors(['email' => 'Access denied. You are not an admin.'])->onlyInput('email');
            }
        }

        \App\Services\AuditLogger::log('login_failed', 'authentication', 'failed', 'Login attempt failed', [
            'attempted_email' => $request->email,
            'reason' => 'Invalid credentials'
        ]);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // --- Logout & Profile ---
    public function logout(Request $request)
    {
        $role = Auth::user() ? Auth::user()->role : null;
        
        if (Auth::user()) {
            \App\Services\AuditLogger::log('logout', 'authentication', 'success', 'User logged out successfully', [
                'user_id' => (string) Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role
            ]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($role === 'admin') {
            return redirect()->route('admin.login');
        }
        return redirect()->route('login');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }
}
