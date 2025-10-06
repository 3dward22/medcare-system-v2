<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    // Show login form
    public function create()
    {
        return view('auth.login');
    }

    // Handle login
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        $user = Auth::user();
/*
        // OTP logic for admin and nurse
        if (in_array($user->role, ['admin', 'nurse'])) {
    $otp = rand(100000, 999999);

    $eloquentUser = User::find($user->id);
    $eloquentUser->otp = $otp;
    $eloquentUser->otp_expires_at = now()->addMinutes(5);
    $eloquentUser->save();

    Mail::to($user->email)->send(new OtpMail($otp));

    // Store user ID in session for OTP verification
    session(['otp_user_id' => $user->id]);

    // Log out until OTP verified
    Auth::logout();

    // Flash flag for modal
    return redirect()->route('login')->with('show_otp_modal', true)
                                     ->with('success', 'OTP sent to your email.');
}
*/
        // Students bypass OTP
        $request->session()->regenerate();

        return match ($user->role) {
            'admin' => redirect()->route('dashboard'),
            'nurse' => redirect()->route('nurse.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login')->with('error', 'Role not recognized.'),
        };
    }

    // Logout
    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
