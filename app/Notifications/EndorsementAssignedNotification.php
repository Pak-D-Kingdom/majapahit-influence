<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class EndorsementAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $brandName,
        public ?string $endorsementTitle = null,
        public ?string $deadline = null,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'endorsement_assigned',
            'title' => 'Endorsement Baru',
            'message' => $this->endorsementTitle
                ? "Anda mendapatkan endorsement: {$this->endorsementTitle}."
                : "Anda mendapatkan endorsement dari {$this->brandName}.",
            'brand_name' => $this->brandName,
            'endorsement_title' => $this->endorsementTitle,
            'deadline' => $this->deadline,
        ];
    }
}