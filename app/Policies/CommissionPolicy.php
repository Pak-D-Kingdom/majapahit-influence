<?php

namespace App\Policies;

use App\Models\Commission;
use App\Models\User;

class CommissionPolicy
{
    public function view(User $user, Commission $commission): bool
    {
        return $user->isSuperadmin()
            || $commission->kolProfile()->where('user_id', $user->id)->exists();
    }

    public function approve(User $user, Commission $commission): bool
    {
        return $user->isSuperadmin();
    }

    public function process(User $user, Commission $commission): bool
    {
        return $user->isSuperadmin();
    }

    public function requestDisbursement(User $user, Commission $commission): bool
    {
        return $user->isSuperadmin()
            || $commission->kolProfile()->where('user_id', $user->id)->exists();
    }
}
