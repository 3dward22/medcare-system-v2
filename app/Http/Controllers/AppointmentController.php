<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    // Ensure user is logged in
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function indexForNurse()
{
    $appointments = Appointment::latest()->paginate(10); // adjust query as needed
    return view('nurse.appointments.index', compact('appointments'));
}

    // Show all appointments for logged-in student
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
            // add other fields as needed
        ]);

        Appointment::create([
            'user_id' => Auth::id(),
            'date' => $request->date,
            'time' => $request->time,
        ]);

        return redirect()->route('student.appointments.index')
                 ->with('success', 'Appointment created successfully.');
    }
}
