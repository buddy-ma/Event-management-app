<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewParticipant extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Event $event,
        public User $participant
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Participant in Your Event')
            ->line("{$this->participant->name} has joined your event: {$this->event->title}")
            ->line("Event Details:")
            ->line("Date: " . $this->event->start_date->format('F j, Y g:i A'))
            ->line("Address: {$this->event->address}")
            ->action('View Event', url("/events/{$this->event->id}"))
            ->line('You can review and manage participants from your event dashboard.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'participant_id' => $this->participant->id,
            'participant_name' => $this->participant->name,
            'type' => 'new_participant',
            'message' => "{$this->participant->name} has joined your event: {$this->event->title}",
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'participant_id' => $this->participant->id,
            'participant_name' => $this->participant->name,
            'type' => 'new_participant',
            'message' => "{$this->participant->name} has joined your event: {$this->event->title}",
        ]);
    }
}