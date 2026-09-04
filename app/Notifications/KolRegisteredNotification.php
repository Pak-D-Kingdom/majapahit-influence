<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class KolRegisteredNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $kolName,
        public ?string $registrationNumber = null,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * This data will be stored in the notifications table.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'kol_registered',
            'title' => 'KOL Baru Terdaftar',
            'message' => "{$this->kolName} telah melakukan pendaftaran sebagai KOL.",
            'kol_name' => $this->kolName,
            'registration_number' => $this->registrationNumber,
        ];
    }
}