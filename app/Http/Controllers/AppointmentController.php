<?php

namespace App\Http\Controllers;



use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Events\NewNotification;
use App\Models\GuardianSmsLog;
use Illuminate\Support\Facades\Http;
use App\Models\AppointmentCompletion;
use Illuminate\Support\Facades\Gate;
class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* ---------------------------------------------------
    | 🩺 Nurse: View all appointments
    --------------------------------------------------- */
    public function indexForNurse()
    {
        $appointments = Appointment::with('student')
            ->orderBy('requested_datetime', 'desc')
            ->paginate(10);

        return view('nurse.appointments.index', compact('appointments'));
    }

    /* ---------------------------------------------------
    | 👩‍🎓 Student: View own appointments
    --------------------------------------------------- */
    public function index()
    {
        $appointments = Appointment::where('student_id', Auth::id())
            ->orderBy('requested_datetime', 'desc')
            ->get();

        return view('students.appointments.index', compact('appointments'));
    }

    /* ---------------------------------------------------
    | 📋 Show details of a specific appointment
    --------------------------------------------------- */
    public function show(Appointment $appointment)
    {
        if ($appointment->student_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('appointments.show', compact('appointment'));
    }

    /* ---------------------------------------------------
    | 📅 Student: Request new appointment
    --------------------------------------------------- */
    public function store(Request $request)
{
    $request->validate([
        'requested_datetime' => [
            'required',
            'date',
            'after:now',
            function ($attribute, $value, $fail) {
                $time = Carbon::parse($value)->format('H:i');
                // ✅ Allowed ranges: 8:00–12:00 and 13:30–16:30
                $withinMorning = ($time >= '08:00' && $time <= '12:00');
                $withinAfternoon = ($time >= '13:30' && $time <= '16:30');
                if (!($withinMorning || $withinAfternoon)) {
                    $fail('Appointments are only allowed between 8:00 AM–12:00 PM or 1:30 PM–4:30 PM.');
                }
            },
        ],
    ]);

    // Daily appointment limit
    $dailyLimit = env('APPOINTMENT_DAILY_LIMIT', 10);
    $appointmentsCount = Appointment::whereDate('requested_datetime', Carbon::parse($request->requested_datetime))
        ->count();

    if ($appointmentsCount >= $dailyLimit) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the appointment limit for this day has been reached. Please choose another date.'
            ], 400);
        }

        return back()->with('error', 'Sorry, the appointment limit for this day has been reached. Please choose another date.');
    }

    Appointment::create([
        'student_id' => Auth::id(),
        'user_id' => Auth::id(),
        'requested_datetime' => $request->requested_datetime,
        'status' => 'pending',
    ]);

    event(new NewNotification("📅 New appointment request from " . Auth::user()->name));

    // ✅ Detect AJAX and respond correctly
    if ($request->expectsJson() || $request->ajax()) {
        return response()->json(['success' => true]);
    }

    return redirect()->route('student.appointments.index')
        ->with('success', 'Appointment request submitted successfully.');
}



    /* ---------------------------------------------------
    | 🧭 Nurse/Admin: Manage appointments (approve, decline, etc.)
    --------------------------------------------------- */
    public function update(Request $request, Appointment $appointment)
{
    try {
        if (!in_array(Auth::user()->role, ['nurse', 'admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'approved_datetime' => 'nullable|date|after:now',
            'status' => 'required|in:approved,rescheduled,declined',
            'admin_note' => 'nullable|string|max:500',
            'findings' => 'nullable|string|max:1000',
        ]);

        $appointment->update([
            'approved_datetime' => $request->approved_datetime,
            'status' => $request->status,
            'approved_by' => Auth::id(),
            'admin_note' => $request->admin_note,
            'findings' => $request->findings,
        ]);

        $student = $appointment->student;

        if (!$student) {
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found for this appointment.'
                ], 404);
            }
            return back()->with('error', 'Student record not found for this appointment.');
        }

        /* ----------------------------
        | 🧠 Notify student in-app
        ---------------------------- */
        $studentMessage = match ($request->status) {
            'approved' => "Your appointment has been approved for " .
                ($appointment->approved_datetime
                    ? Carbon::parse($appointment->approved_datetime)->format('M d, Y h:i A')
                    : 'the scheduled date') . ".",
            'declined' => "Your appointment request was declined by the nurse.",
            'rescheduled' => "Your appointment has been rescheduled. Please check your dashboard for details.",
            'completed' => "Your check-up has been completed. Thank you for visiting the clinic.",
            default => "Your appointment status was updated.",
        };

        event(new NewNotification("📢 {$studentMessage}", $student->id));
        $appointment->update(['student_sms_sent' => true]);

         // Optional SMS sending (if using real API)
        /*
        Http::post('https://api.semaphore.co/api/v4/messages', [
            'apikey' => env('SEMAPHORE_API_KEY'),
            'number' => $student->phone,
            'message' => $studentMessage,
            'sendername' => 'MedCare',
        ]);
        */
        /* ----------------------------
        | 👨‍👩‍👧 Notify Guardian via SMS
        ---------------------------- */
        if (!empty($student->guardian_phone)) {
            $guardianMessage = match ($request->status) {
                'approved' => "Good day! This is MedCare. {$student->name}'s appointment has been approved.",
                'declined' => "Hello! {$student->name}'s appointment request was declined by the nurse.",
                'rescheduled' => "Heads up! {$student->name}'s appointment has been rescheduled.",
                'completed' => "MedCare update: {$student->name}'s check-up is completed. Findings: " .
                    ($request->findings ?? 'No findings recorded.'),
                default => "Update: {$student->name}'s appointment status changed.",
            };

            $this->sendGuardianSms($appointment, $student, $guardianMessage);
        }

        /* ----------------------------
        | ✅ Return success response
        ---------------------------- */
        if ($request->wantsJson() || $request->ajax() || $request->header('Accept') === 'application/json') {
    return response()->json(['success' => true]);
}

return redirect()->back()->with('success', 'Appointment updated and all notifications sent successfully.');

    } catch (\Exception $e) {
        Log::error('Appointment update failed: ' . $e->getMessage());

        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }

        return back()->with('error', 'An unexpected error occurred while updating the appointment.');
    }
}

    public function complete(Request $request, Appointment $appointment)
{
    if (!in_array(Auth::user()->role, ['nurse', 'admin'])) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'completed_datetime' => 'required|date',
        'temperature' => 'nullable|string|max:50',
        'blood_pressure' => 'nullable|string|max:50',
        'heart_rate' => 'nullable|string|max:50',
        'findings' => 'nullable|string|max:1000', // ✅ For diagnosis/findings
        'additional_notes' => 'nullable|string|max:1000', // ✅ Extra notes
    ]);

    // ✅ Create new completion record
    AppointmentCompletion::create([
        'appointment_id' => $appointment->id,
        'completed_datetime' => $request->completed_datetime,
        'temperature' => $request->temperature,
        'blood_pressure' => $request->blood_pressure,
        'heart_rate' => $request->heart_rate,
        'findings' => $request->findings, // ✅ Primary findings/diagnosis
        'additional_notes' => $request->additional_notes, // ✅ Extra commentary
    ]);

    // ✅ Mark main record as completed
    $appointment->update([
        'status' => 'completed',
        'approved_by' => Auth::id(),
    ]);

    // ✅ Notify student
    event(new NewNotification(
        "✅ Your check-up is completed. Please review the clinic notes.", 
        $appointment->student_id
    ));

    return response()->json([
        'success' => true, 
        'message' => 'Appointment marked as completed.'
    ]);
}
public function storeEmergency(Request $request)
{
    if (!Gate::allows('is-nurse-or-admin')) {
        abort(403, 'Unauthorized access.');
    }

    $request->validate([
        'student_id' => 'required|exists:users,id',
        'reason' => 'nullable|string|max:500',
        'completed_datetime' => 'required|date',
        'temperature' => 'nullable|string|max:50',
        'blood_pressure' => 'nullable|string|max:50',
        'heart_rate' => 'nullable|string|max:50',
        'additional_notes' => 'nullable|string|max:1000',
        'findings' => 'nullable|string|max:1000',
    ]);

    // ✅ Step 1: Create appointment and mark as completed
    $appointment = Appointment::create([
        'student_id'        => $request->student_id,
        'user_id'           => $request->student_id, // Student is the user
        'requested_datetime'=> now(),
        'approved_datetime' => now(),
        'completed_datetime'=> $request->completed_datetime,
        'status'            => 'completed',
        'admin_note'        => $request->reason ?? 'Emergency / Walk-in case',
        'approved_by'       => Auth::id(),
        'temperature'       => $request->temperature,
        'blood_pressure'    => $request->blood_pressure,
        'heart_rate'        => $request->heart_rate,
        'additional_notes'  => $request->additional_notes,
        'findings'          => $request->findings,
    ]);

    // ✅ Step 2: Store full completion details in AppointmentCompletion table
    AppointmentCompletion::create([
        'appointment_id'    => $appointment->id,
        'completed_datetime'=> $request->completed_datetime,
        'temperature'       => $request->temperature,
        'blood_pressure'    => $request->blood_pressure,
        'heart_rate'        => $request->heart_rate,
        'findings'          => $request->findings,
        'additional_notes'  => $request->additional_notes,
    ]);

    // ✅ Step 3: Notify student (optional)
    event(new NewNotification(
        "🚨 You had an emergency check-up. Please check your medical record.", 
        $request->student_id
    ));

    return response()->json([
        'success' => true,
        'message' => 'Emergency appointment recorded and marked as completed.'
    ]);
}




    /* ---------------------------------------------------
    | ❌ Student: Cancel appointment
    --------------------------------------------------- */
    public function destroy(Appointment $appointment)
    {
        if ($appointment->student_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $appointment->update(['status' => 'cancelled']);

        event(new NewNotification("❌ Appointment #{$appointment->id} was cancelled by " . Auth::user()->name));

        return back()->with('success', 'Your appointment has been cancelled.');
    }

    /* ---------------------------------------------------
    | 📊 Admin: Dashboard overview
    --------------------------------------------------- */
    public function allAppointments()
    {
        $allAppointments = Appointment::orderBy('requested_datetime', 'desc')->get();

        $today = Carbon::today();
        $todayAppointments = Appointment::whereDate('requested_datetime', $today)
            ->orderBy('requested_datetime', 'desc')->get();

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $weekAppointments = Appointment::whereBetween('requested_datetime', [$startOfWeek, $endOfWeek])
            ->orderBy('requested_datetime', 'desc')->get();

        return view('admin.appointments.all', [
            'allAppointments' => $allAppointments,
            'todayAppointments' => $todayAppointments,
            'weekAppointments' => $weekAppointments,
        ]);
    }

    /* ---------------------------------------------------
    | 🔁 Real-time JSON for Nurse Dashboard
    --------------------------------------------------- */
    public function todayAppointmentsJson()
    {
        $today = Carbon::today();

        $todayAppointments = Appointment::with('user')
            ->whereDate('requested_datetime', $today)
            ->orderBy('requested_datetime', 'desc')
            ->get();

        return response()->json([
            'count' => $todayAppointments->count(),
            'appointments' => $todayAppointments,
        ]);
    }

    /* ---------------------------------------------------
    | 💬 Notify Guardian (Manual Button)
    --------------------------------------------------- */
    public function notifyGuardian(Request $request, $appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $student = $appointment->student;

        if (!$student || !$student->guardian_phone) {
            return back()->with('error', 'Guardian contact not found for this student.');
        }

        $message = "Hello {$student->guardian_name}, this is MedCare. 
        Your child {$student->name} had a check-up today. 
        Diagnosis/Notes: " . ($appointment->findings ?? 'No findings available.');

        $this->sendGuardianSms($appointment, $student, $message);

        if ($request->ajax()) {
            return response()->json(['success' => 'Guardian SMS sent and logged successfully.']);
        }

        return back()->with('success', 'Guardian SMS sent and logged successfully.');
    }

    /* ---------------------------------------------------
    | 🧩 Helper: Log and mark Guardian SMS
    --------------------------------------------------- */
    private function sendGuardianSms($appointment, $student, $message)
    {
        GuardianSmsLog::create([
            'appointment_id' => $appointment->id,
            'student_id' => $student->id,
            'guardian_name' => $student->guardian_name,
            'guardian_phone' => $student->guardian_phone,
            'message' => $message,
            'sent_by' => Auth::user()->name,
            'sent_by_id' => Auth::id(),
            'sent_by_role' => Auth::user()->role,
            'sent_at' => now(),
        ]);

        $appointment->update(['guardian_sms_sent' => true]);
    }
}