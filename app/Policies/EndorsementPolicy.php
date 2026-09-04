<?php

namespace App\Policies;

use App\Models\Endorsement;
use App\Models\User;

class EndorsementPolicy
{
    public function view(User $user, Endorsement $endorsement): bool
    {
        return $user->hasRole('superadmin')
            || $endorsement->kolProfile()->where('user_id', $user->id)->exists();
    }

    public function update(User $user, Endorsement $endorsement): bool
    {
        return $user->hasRole('superadmin')
            || $endorsement->kolProfile()->where('user_id', $user->id)->exists();
    }

    public function delete(User $user, Endorsement $endorsement): bool
    {
        return $user->hasRole('superadmin');
    }
}
