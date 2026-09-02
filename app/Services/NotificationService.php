<?php

namespace App\Services;

use App\Models\Endorsement;
use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public function send(User $user, string $type, string $title, string $body, ?string $targetUrl = null): Notification
    {
        return $user->notifications()->create([
            'type' => $type,
            'title' => $title,
            'body' => $body,
            'target_url' => $targetUrl,
        ]);
    }

    public function notifySuperadmins(string $type, string $title, string $body, ?string $targetUrl = null): void
    {
        User::whereHas('roles', fn ($query) => $query->where('name', 'superadmin'))
            ->each(fn (User $user) => $this->send($user, $type, $title, $body, $targetUrl));
    }

    public function endorsementAssigned(Endorsement $endorsement): void
    {
        $endorsement->loadMissing(['kolProfile.user', 'campaign']);
        if ($endorsement->kolProfile?->user) {
            $this->send($endorsement->kolProfile->user, 'endorsement_assigned', 'Endorsement baru', 'Kamu mendapatkan tugas endorsement '.$endorsement->campaign->name.'.', route('kol.endorsements.show', $endorsement));
        }
    }
}
