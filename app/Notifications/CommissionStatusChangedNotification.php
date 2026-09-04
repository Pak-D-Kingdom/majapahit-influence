<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommissionStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $endorsementTitle,
        public string $status,
        public ?string $note = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'commission_status_changed',
            'title' => 'Status Komisi Berubah',
            'message' => "Status komisi untuk {$this->endorsementTitle} berubah menjadi {$this->status}.",
            'endorsement_title' => $this->endorsementTitle,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}