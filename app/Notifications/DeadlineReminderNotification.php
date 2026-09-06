<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DeadlineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $endorsementTitle,
        public string $deadline,
        public int $daysRemaining,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'deadline_reminder',
            'title' => 'Reminder Deadline',
            'message' => "Deadline endorsement {$this->endorsementTitle} tinggal H-{$this->daysRemaining}.",
            'endorsement_title' => $this->endorsementTitle,
            'deadline' => $this->deadline,
            'days_remaining' => $this->daysRemaining,
        ];
    }
}