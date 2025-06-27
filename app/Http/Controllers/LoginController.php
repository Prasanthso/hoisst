<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PasswordReset;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // blade file you posted
    }

    public function showForgotPasswordForm()
    {
        return view('forgetPassword');
    }

    public function sendResetLink(Request $request)
    {
        // Validate the email
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Generate a password reset token
            $token = Str::random(60);

            // Remove old tokens for this email
            PasswordReset::where('email', $request->email)->delete();

            // Insert the new token
            PasswordReset::create([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send the reset link to the user's email (This is a simple email implementation)
            $resetUrl = route('password.reset', ['token' => $token, 'email' => $request->email]);
            Mail::send('emails.password-reset', ['resetUrl' => $resetUrl], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Password Reset Link');
            });

            return back()->with('success', 'We have emailed your password reset link!');
        }

        return back()->withErrors(['email' => 'No user found with that email address.']);
    }

    public function showResetPasswordForm($token, $email)
    {
        return view('resetPassword', ['token' => $token, 'email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        // Validate the input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        // Check if the reset token is valid
        $resetRecord = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['email' => 'Invalid or expired reset token.']);
        }

        // Find the user and update their password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the reset record to prevent reusing the token
        PasswordReset::where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset!');
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
