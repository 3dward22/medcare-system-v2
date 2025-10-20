<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Patient;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $user = Auth::user();

        // 🔑 Check role and show correct dashboard
        switch ($user->role) {
            case 'admin':
                $patientsCount = Patient::count();
                $appointmentsCount = Appointment::count();

                return view('dashboard', [
                    'patientsCount' => $patientsCount,
                    'appointmentsCount' => $appointmentsCount
                ]);

            case 'nurse':
                $todayAppointments = Appointment::with('user')
                     ->whereDate('requested_datetime', Carbon::today())
                     ->orderBy('requested_datetime', 'asc')
                     ->get();

                 $upcomingAppointments = Appointment::with('user')
                    ->where('requested_datetime', '>=', Carbon::today())
                    ->orderBy('requested_datetime', 'asc')
                    ->get();

                $students = User::where('role', 'student')->get();

                return view('nurse.dashboard', [
    'todayAppointments' => $todayAppointments,
    'upcomingAppointments' => $upcomingAppointments,
    'students' => $students   // ✅ Add this
]);



            case 'student':
                $appointments = Appointment::where('user_id', $user->id)->get();

                return view('students.dashboard', [
                    'appointments' => $appointments
                ]);

            default:
                abort(403, 'Unauthorized');
        }
    }
}
