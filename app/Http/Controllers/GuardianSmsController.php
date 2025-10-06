<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuardianSmsLog;

class GuardianSmsController extends Controller
{
    // 🧾 Show all guardian SMS logs
    public function index()
    {
        $logs = GuardianSmsLog::latest()->paginate(10);
        return view('guardian_sms.index', compact('logs'));
    }

    // 💬 Send message to guardian (for nurse)
    public function send(Request $request)
    {
        $request->validate([
            'guardian_name' => 'required|string',
            'guardian_phone' => 'required|string',
            'message' => 'required|string|max:255',
        ]);

        GuardianSmsLog::create([
            'guardian_name' => $request->guardian_name,
            'guardian_phone' => $request->guardian_phone,
            'message' => $request->message,
            'sent_by' => \Illuminate\Support\Facades\Auth::user()->name,
        ]);

        return back()->with('success', 'SMS sent and logged successfully.');
    }
}
