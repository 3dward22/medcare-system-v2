<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewNotification extends Notification
{
    use Queueable;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // store in DB + broadcast
    }

    public function toDatabase($notifiable)
    {
        return ['message' => $this->message];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage(['message' => $this->message]);
    }
}
