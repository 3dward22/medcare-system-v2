<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // List all students for the nurse to chat with
    public function listStudents()
{
    $students = User::where('role', 'student')->get();
    return view('nurse.chat.students', compact('students'));
}
    // Show chat with a specific student
    public function indexWithStudent(User $student)
    {
        $nurse = Auth::user();

        // Fetch messages between nurse and this student
        $messages = Chat::where(function($q) use ($nurse, $student){
            $q->where('sender_id', $nurse->id)->where('receiver_id', $student->id);
        })->orWhere(function($q) use ($nurse, $student){
            $q->where('sender_id', $student->id)->where('receiver_id', $nurse->id);
        })->orderBy('created_at', 'asc')->get();

        return view('nurse.chat.chat_with_student', compact('student', 'messages'));
    }

    
    // Send message to a specific student
    public function sendMessage(Request $request, User $student)
    {
    $request->validate([
        'message' => 'required|string|max:500'
    ]);

    $nurse = Auth::user();

    $chat = Chat::create([
        'sender_id'   => $nurse->id,
        'receiver_id' => $student->id,
        'message'     => $request->message,
    ]);

    // Make sure sender relationship is available
    $chat->load('sender');

    // JSON response for AJAX clients
    if ($request->wantsJson() || $request->isJson()) {
        return response()->json([
            'id'          => $chat->id,
            'sender_name' => $chat->sender->name,
            'message'     => $chat->message,
            'time'        => $chat->created_at->format('H:i, d M'),
            'is_mine'     => $chat->sender_id == Auth::id(),
        ]);
    }

    return redirect()->route('nurse.chat.with_student', $student->id);
}

    // Fetch messages between nurse and a student
    public function fetchMessages(User $student)
{
    $nurse = Auth::user();

    $messages = Chat::with('sender')
        ->where(function($q) use ($nurse, $student) {
            $q->where('sender_id', $nurse->id)->where('receiver_id', $student->id);
        })->orWhere(function($q) use ($nurse, $student) {
            $q->where('sender_id', $student->id)->where('receiver_id', $nurse->id);
        })->orderBy('created_at', 'asc')->get();

    $formatted = $messages->map(function($msg) {
        return [
            'id'          => $msg->id,
            'sender_name' => $msg->sender->name,
            'message'     => $msg->message,
            'time'        => $msg->created_at->format('H:i, d M'),
            'is_mine'     => $msg->sender_id == Auth::id(),
        ];
    });

    return response()->json($formatted);
}

}
