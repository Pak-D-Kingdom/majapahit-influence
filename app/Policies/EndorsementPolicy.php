<?php

namespace App\Policies;

use App\Models\Endorsement;
use App\Models\User;

class EndorsementPolicy
{
    public function view(User $user, Endorsement $endorsement): bool
    {
        return $user->isSuperadmin() || $endorsement->kolProfile?->user_id === $user->id;
    }

    public function update(User $user, Endorsement $endorsement): bool
    {
        return $user->isSuperadmin();
    }
}
