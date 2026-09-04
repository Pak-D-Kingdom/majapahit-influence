<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProofSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $kolName,
        public string $endorsementTitle,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'proof_submitted',
            'title' => 'Proof Konten Dikirim',
            'message' => "{$this->kolName} telah mengirimkan proof untuk endorsement {$this->endorsementTitle}.",
            'kol_name' => $this->kolName,
            'endorsement_title' => $this->endorsementTitle,
        ];
    }
}