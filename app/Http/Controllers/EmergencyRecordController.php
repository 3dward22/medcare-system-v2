<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use App\Models\EmergencyRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Carbon;

class EmergencyRecordController extends Controller
{
    public function index(Request $request)
    {
        // Filters: all | today | week
        $filter = $request->get('filter', 'all');

        $query = EmergencyRecord::with(['student', 'nurse'])->latest();

        if ($filter === 'today') {
            $query->whereDate('reported_at', Carbon::today());
        } elseif ($filter === 'week') {
            $query->whereBetween('reported_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        }

        $records = $query->paginate(10)->withQueryString();

        return view('nurse.emergency.index', compact('records', 'filter'));
    }

    public function create()
    {
        // Students to select (searchable select UX will be on the view)
        $students = User::where('role', 'student')
            ->orderBy('name')
            ->select('id', 'name')
            ->get();

        return view('nurse.emergency.create', compact('students'));
    }

    public function store(Request $request)
    {
        Gate::authorize('is-nurse-or-admin'); // optional: or just check role manually

        $request->validate([
            'student_id'            => 'required|exists:users,id',
            'reported_at'           => 'required|date',
            'temperature'           => 'required|string|max:50',
            'blood_pressure'        => 'required|string|max:50',
            'heart_rate'            => 'required|string|max:50',
            'symptoms'              => 'required|string|max:2000',
            'diagnosis'             => 'required|string|max:2000',
            'treatment'             => 'required|string|max:2000',
            'additional_notes'      => 'nullable|string|max:2000',
            'guardian_notified'     => 'required|boolean',
            'guardian_notified_at'  => 'nullable|date|required_if:guardian_notified,1',
        ], [
            'guardian_notified_at.required_if' => 'Please provide the Guardian Notification Time when Guardian Notified is Yes.',
        ]);

        $record = EmergencyRecord::create([
            'student_id'           => $request->student_id,
            'reported_by'          => Auth::id(),
            'reported_at'          => $request->reported_at,
            'temperature'          => $request->temperature,
            'blood_pressure'       => $request->blood_pressure,
            'heart_rate'           => $request->heart_rate,
            'symptoms'             => $request->symptoms,
            'diagnosis'            => $request->diagnosis,
            'treatment'            => $request->treatment,
            'additional_notes'     => $request->additional_notes,
            'guardian_notified'    => (bool)$request->guardian_notified,
            'guardian_notified_at' => $request->guardian_notified ? $request->guardian_notified_at : null,
        ]);

        // In-app notification to student
        event(new NewNotification(
            "🚑 An emergency case report was recorded. Please check your records.",
            $record->student_id
        ));

        return redirect()
            ->route('nurse.emergency.index')
            ->with('success', 'Emergency report saved successfully.');
    }

    public function show(EmergencyRecord $emergency)
    {
        $emergency->load(['student', 'nurse']);
        return view('nurse.emergency.show', compact('emergency'));
    }
}
