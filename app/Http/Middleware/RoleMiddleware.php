<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    if (Auth::user()->role !== $role) {
        // Redirect to the correct dashboard based on role
        $userRole = strtolower(Auth::user()->role);
        return match ($userRole) {
            'admin' => redirect()->route('dashboard'),
            'nurse' => redirect()->route('nurse.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            default => redirect()->route('login'),
        };
    }

    return $next($request);
}

}
