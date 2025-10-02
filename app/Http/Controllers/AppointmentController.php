<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Events\NewNotification;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 🩺 Nurse: Show all appointments
    public function indexForNurse()
    {
        $appointments = Appointment::with('student')
            ->orderBy('requested_datetime', 'asc')
            ->paginate(10);

        return view('nurse.appointments.index', compact('appointments'));
    }

    // 👩‍🎓 Student: Show their own appointments
    public function index()
    {
        $appointments = Appointment::where('student_id', Auth::id())
            ->orderBy('requested_datetime', 'asc')
            ->get();

        return view('students.appointments.index', compact('appointments'));
    }

    // Show details of a single appointment
    public function show(Appointment $appointment)
    {
        if ($appointment->student_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('appointments.show', compact('appointment'));
    }

    // 📅 Store a new appointment request (STUDENT)
    public function store(Request $request)
    {
        $request->validate([
            'requested_datetime' => 'required|date|after:now',
        ]);

        // ✅ Enforce daily limit
        $dailyLimit = env('APPOINTMENT_DAILY_LIMIT', 10);
        $appointmentsCount = Appointment::whereDate('requested_datetime', Carbon::parse($request->requested_datetime))
            ->count();

        if ($appointmentsCount >= $dailyLimit) {
            return back()->with('error', 'Sorry, the appointment limit for this day has been reached. Please choose another date.');
        }

        $appointment = Appointment::create([
            'student_id' => Auth::id(),
            'user_id'    => Auth::id(),
            'requested_datetime' => $request->requested_datetime,
            'status' => 'pending',
        ]);

        // 🔔 Notify admin/nurse about new booking
        event(new NewNotification("📅 New appointment request from " . Auth::user()->name));

        return redirect()->route('student.appointments.index')
            ->with('success', 'Appointment request submitted successfully.');
    }

    // ✅ Nurse/Admin: Approve, Reschedule, Decline, Complete
    public function update(Request $request, Appointment $appointment)
    {
        // Make sure only nurse or admin can update
        if (!in_array(Auth::user()->role, ['nurse', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'approved_datetime' => 'nullable|date|after:now',
            'status' => 'required|in:approved,rescheduled,declined,completed',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $appointment->update([
            'approved_datetime' => $request->approved_datetime,
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'admin_note' => $request->admin_note,
        ]);

        // 🔔 Fire real-time notification for update
        event(new NewNotification("📢 Appointment #{$appointment->id} has been {$request->status} by " . Auth::user()->name));

        return back()->with('success', 'Appointment updated successfully.');
    }

    // ❌ Student: Cancel own appointment
    public function destroy(Appointment $appointment)
    {
        if ($appointment->student_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $appointment->update(['status' => 'cancelled']);

        // 🔔 Notify cancellation
        event(new NewNotification("❌ Appointment #{$appointment->id} was cancelled by " . Auth::user()->name));

        return back()->with('success', 'Your appointment has been cancelled.');
    }

    // 📊 Admin: View all + Today + Week (Dashboard)
    public function allAppointments()
    {
        $allAppointments = Appointment::orderBy('requested_datetime', 'asc')->get();

        $today = Carbon::today();
        $todayAppointments = Appointment::whereDate('requested_datetime', $today)
            ->orderBy('requested_datetime', 'asc')
            ->get();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weekAppointments = Appointment::whereBetween('requested_datetime', [$startOfWeek, $endOfWeek])
            ->orderBy('requested_datetime', 'asc')
            ->get();

        return view('admin.appointments.all', [
            'allAppointments' => $allAppointments,
            'todayAppointments' => $todayAppointments,
            'weekAppointments' => $weekAppointments,
        ]);
    }

    // 🔄 Real-time endpoint for Nurse Dashboard (JSON)
    public function todayAppointmentsJson()
    {
        $today = Carbon::today();

        $todayAppointments = Appointment::with('user')
            ->whereDate('requested_datetime', $today)
            ->orderBy('requested_datetime', 'asc')
            ->get();

        return response()->json([
            'count' => $todayAppointments->count(),
            'appointments' => $todayAppointments,
        ]);
    }
}
