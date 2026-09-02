<?php

namespace App\Policies;

use App\Models\KolProfile;
use App\Models\User;

class KolProfilePolicy
{
    public function view(User $user, KolProfile $kolProfile): bool
    {
        return $user->isSuperadmin() || $kolProfile->user_id === $user->id;
    }

    public function update(User $user, KolProfile $kolProfile): bool
    {
        return $user->isSuperadmin() || $kolProfile->user_id === $user->id;
    }
}
