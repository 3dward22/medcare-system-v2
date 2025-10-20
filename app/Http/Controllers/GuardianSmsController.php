<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuardianSmsLog;
use Illuminate\Support\Facades\Auth;

class GuardianSmsController extends Controller
{
    // 🧾 Show guardian SMS logs (with search, filter & role logic)
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = GuardianSmsLog::query();

        // 🔒 Role-based access
        if ($user->role === 'nurse') {
            $query->where('sent_by_id', $user->id);
        }

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('guardian_name', 'like', "%{$search}%")
                  ->orWhere('guardian_phone', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        // 🧑‍⚕️ Filter by nurse (admin only)
        if ($user->role === 'admin' && $request->filled('nurse')) {
            $query->where('sent_by', $request->nurse);
        }

        // 📅 Filter by date range
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        // 🔽 Sorting (newest or oldest)
        $sort = $request->get('sort', 'desc');
        $logs = $query->orderBy('created_at', $sort)->paginate(10);

        // Distinct nurse names (for dropdown filter)
        $nurses = GuardianSmsLog::select('sent_by')->distinct()->pluck('sent_by');

        return view('guardian_sms.index', compact('logs', 'nurses', 'sort'));
    }

    // 💬 Send message to guardian (for nurse)
    public function send(Request $request)
    {
        $request->validate([
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'message' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        GuardianSmsLog::create([
            'guardian_name'  => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'message'        => $request->message,
            'sent_by'        => $user->name,
            'sent_by_id'     => $user->id,
            'sent_by_role'   => $user->role,
        ]);

        return back()->with('success', 'SMS sent and logged successfully.');
    }
}
