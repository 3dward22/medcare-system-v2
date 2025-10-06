<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;

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
        $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password'       => ['required', 'confirmed', Password::defaults()],
            'role'           => ['required', 'in:admin,nurse,student'],
            'student_phone'  => ['nullable', 'string', 'max:20'],
            'guardian_name'  => ['nullable', 'string', 'max:255'],
            'guardian_phone' => ['nullable', 'string', 'max:20'],
            'admin_code'     => ['nullable', 'string'],
        ]);

        $role = strtolower($request->role);
/*
        // 🔒 Admin role validation
        if ($role === 'admin') {
            $adminSecret = env('ADMIN_SECRET');
            if (empty($adminSecret) || $request->admin_code !== $adminSecret) {
                return back()
                    ->withErrors(['admin_code' => 'Invalid admin access code.'])
                    ->withInput()
                    ->with('show_admin_modal', true); // ✅ Keeps modal open
            }
        }
*/
        // 🎓 Student guardian validation
        if ($role === 'student') {
            $request->validate([
                'student_phone'  => ['required', 'string', 'max:20'],
                'guardian_name'  => ['required', 'string', 'max:255'],
                'guardian_phone' => ['required', 'string', 'max:20'],
            ]);
        }

        // 🧍 Create user
        $user = User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'role'           => $role,
            'student_phone'  => $request->student_phone,
            'guardian_name'  => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
        ]);

        event(new Registered($user));
        Auth::login($user);

        // 🧭 Role-based redirect
        switch ($user->role) {
            case 'admin':
                session()->flash('success', 'Welcome Admin! You have registered successfully.');
                return redirect()->intended(route('dashboard'));

            case 'nurse':
                session()->flash('success', 'Welcome Nurse! You have registered successfully.');
                return redirect()->intended(route('nurse.dashboard'));

            case 'student':
                session()->flash('success', 'Welcome Student! You have registered successfully.');
                return redirect()->intended(route('student.dashboard'));

            default:
                Auth::logout();
                session()->flash('error', 'Role not recognized, please contact support.');
                return redirect()->route('login');
        }
    }
}
