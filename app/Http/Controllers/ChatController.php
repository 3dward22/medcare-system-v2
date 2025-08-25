<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Show chat page
    public function index()
    {
        return view('chat.index'); // We'll create this view next
    }

    // Optional: store/send message
    public function store(Request $request)
    {
        // For now, just validate and pretend to save messages
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // You can later save it to database
        // Message::create([...]);

        return back()->with('success', 'Message sent!');
    }
}
