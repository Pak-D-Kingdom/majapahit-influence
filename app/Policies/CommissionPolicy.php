<?php

namespace App\Policies;

use App\Models\Commission;
use App\Models\User;

class CommissionPolicy
{
    public function view(User $user, Commission $commission): bool
    {
        return $user->isSuperadmin() || $commission->kolProfile?->user_id === $user->id;
    }

    public function requestDisbursement(User $user, Commission $commission): bool
    {
        return $user->isKol() && $commission->kolProfile?->user_id === $user->id;
    }
}
