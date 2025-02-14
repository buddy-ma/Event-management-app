<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class EventJoinedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $joiningUser;

    public function __construct($event, $joiningUser)
    {
        $this->event = $event;
        $this->joiningUser = $joiningUser;
    }

    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'id' => $this->id,
            'data' => [
                'event_id' => $this->event->id,
                'event_title' => $this->event->title,
                'user_id' => $this->joiningUser->id,
                'user_name' => $this->joiningUser->name,
            ],
            'created_at' => now()->toISOString(),
            'read_at' => null,
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'user_id' => $this->joiningUser->id,
            'user_name' => $this->joiningUser->name,
        ];
    }
}