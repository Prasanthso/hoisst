<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use App\Models\User;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // blade file you posted
    }

    public function verifyLogin(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt(['email' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            // ✅ Custom session data
            $user = Auth::user();
            session([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role ?? 'user', // If you have a role column
                'store_id' => $user->store_id,
                // Add more if needed
            ]);

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login_failed' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // This clears *all* session data
        $request->session()->invalidate();

        // Regenerates CSRF token
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
