<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    // Ensure user is logged in
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Nurse: show all appointments
    public function indexForNurse()
    {
        $appointments = Appointment::latest()->paginate(10);
        return view('nurse.appointments.index', compact('appointments'));
    }

    // Student: show all appointments for logged-in student
    public function index()
    {
        $appointments = Appointment::where('user_id', Auth::id())->get();
        return view('students.appointments.index', compact('appointments'));
    }

    // Show a single appointment
    public function show(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('appointments.show', compact('appointment'));
    }

    // Store a new appointment
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
        ]);

        Appointment::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'time' => $request->time,
        ]);

        return redirect()->route('student.appointments.index')
                 ->with('success', 'Appointment created successfully.');
    }

    // ✅ Admin: View ALL appointments (and also prepare Today + Week data for modals)
    public function allAppointments()
    {
        $allAppointments = Appointment::orderBy('appointment_date', 'asc')->get();

        $today = Carbon::today();
        $todayAppointments = Appointment::whereDate('appointment_date', $today)
                                        ->orderBy('appointment_date', 'asc')
                                        ->get();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weekAppointments = Appointment::whereBetween('appointment_date', [$startOfWeek, $endOfWeek])
                                       ->orderBy('appointment_date', 'asc')
                                       ->get();

        return view('admin.appointments.all', [
            'allAppointments' => $allAppointments,
            'todayAppointments' => $todayAppointments,
            'weekAppointments' => $weekAppointments,
        ]);
    }
}
