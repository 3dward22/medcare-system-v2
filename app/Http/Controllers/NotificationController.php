<?php

namespace App\Http\Controllers;

use App\Events\NewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    // Polling endpoint your JS calls: route('notifications.check')
    // app/Http/Controllers/NotificationController.php

public function check()
{
    $user = Auth::user();

    $unread = $user->notifications()       // <-- relation is always known to Intelephense
        ->whereNull('read_at')             // <-- replaces unreadNotifications()
        ->orderBy('created_at', 'desc')
        ->take(20)
        ->get(['id', 'data', 'created_at']);

    return response()->json([
        'count' => $unread->count(),
        'notifications' => $unread->map(fn($n) => [
            'id' => $n->id,
            'message' => $n->data['message'] ?? 'New notification',
            'created_at' => $n->created_at->toDateTimeString(),
        ]),
    ]);
}

public function index()
{
    return Auth::user()->notifications()   // <-- relation
        ->whereNull('read_at')             // <-- unread
        ->orderBy('created_at', 'desc')
        ->get();
}

public function markAllAsRead()
{
    Auth::user()->notifications()
        ->whereNull('read_at')
        ->update(['read_at' => now()]);

    return response()->json(['success' => true]);
}


    // Trigger a new notification (manual send)
    public function sendNotification(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:255',
        ]);

        $user = \App\Models\User::find($data['user_id']);

        // Store in DB (anonymous notification is fine)
        $user->notify(new class($data['message']) extends \Illuminate\Notifications\Notification {
            public function __construct(private string $message) {}
            public function via($notifiable) { return ['database']; }
            public function toDatabase($notifiable) { return ['message' => $this->message]; }
        });

        // Broadcast live to user.{id}
        broadcast(new NewNotification($data['user_id'], $data['message']))->toOthers();

        return response()->json(['success' => true]);
    }
}
