<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function check()
{
    $user = Auth::user();

    $unread = $user->unreadNotifications->take(10);

    return response()->json([
        'count' => $user->unreadNotifications->count(),
        'notifications' => $unread->map(function ($n) {
            return [
                'id' => $n->id,
                'message' => $n->data['message'] ?? 'New notification',
                'created_at' => $n->created_at->diffForHumans(),
            ];
        }),
    ]);
}


}