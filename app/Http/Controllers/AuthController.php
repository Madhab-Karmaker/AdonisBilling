<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Service;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
           
        ]);
        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Redirect based on user role
            if ($user->role === 'manager') {
                return redirect()->intended('/dashboard/manager');
            } else {
                return redirect()->intended('/dashboard/receptionist');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }

    /**
     * Show manager dashboard
     */
    public function managerDashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'manager') {
            abort(403, 'Unauthorized access');
        }
        $salon = $user->salon; // Get the salon for this manager
        $totalServices = Service::count(); // Scoped by salon via global scope
        return view('dashboard.manager', compact('user', 'totalServices'));
    }

    /**
     * Show receptionist dashboard
     */
    public function receptionistDashboard()
    {
        $user = Auth::user();
        
        if ($user->role !== 'receptionist') {
            abort(403, 'Unauthorized access');
        }
        $salon = $user->salon; // Get the salon for this receptionist
        return view('dashboard.receptionist', compact('user'));
    }
}
