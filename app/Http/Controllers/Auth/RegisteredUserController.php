<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration form.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role'     => 'required|string|in:admin,nurse,student',
        ]);

        // Normalize role (avoid capitalization issues)
        $role = strtolower($request->role);

        // Create user
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
        ]);

        // Automatically log in the user
        Auth::login($user);

        // Role-based redirect
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard')
                    ->with('success', 'Welcome Admin! You have registered successfully.');
            case 'nurse':
                 return redirect()->route('nurse.dashboard')
                    ->with('success', 'Welcome Nurse! You have registered successfully.');

            case 'student':
                return redirect()->route('student.dashboard')
                    ->with('success', 'Welcome Student! You have registered successfully.');

            default:
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Role not recognized, please contact support.');
        }
    }
}
