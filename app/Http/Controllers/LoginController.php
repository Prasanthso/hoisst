<?php 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // Ensure the file is in resources/views/login.blade.php
    }

    public function verifyLogin(Request $request)
    {
        // Validate input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Retrieve input
        $username = $request->input('username');
        $password = $request->input('password');

        // Check credentials in the database
        $user = DB::table('login')->where('username', $username)->first();

        if ($user && $user->password === $password) {
            // Login successful, set success message in session
            return redirect()->route('login')->with('success', 'Login successful!');
        }

        // Login failed
        return redirect()->back()->withErrors(['login_failed' => 'Invalid username or password.']);
    }
}
