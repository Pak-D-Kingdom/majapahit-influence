<?php

namespace App\Policies;

use App\Models\KolProfile;
use App\Models\User;

class KolProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('superadmin') || $user->hasRole('kol');
    }

    public function view(User $user, KolProfile $profile): bool
    {
        return $user->hasRole('superadmin') || $profile->user_id === $user->id;
    }

    public function update(User $user, KolProfile $profile): bool
    {
        return $user->hasRole('superadmin') || $profile->user_id === $user->id;
    }

    public function delete(User $user, KolProfile $profile): bool
    {
        return $user->hasRole('superadmin');
    }
}
