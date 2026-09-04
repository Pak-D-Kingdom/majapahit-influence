<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('superadmin') || $user->hasRole('kol');
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return $user->hasRole('superadmin')
            || $campaign->endorsements()->whereHas('kolProfile', fn ($query) => $query->where('user_id', $user->id))->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('superadmin');
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $user->hasRole('superadmin');
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->hasRole('superadmin');
    }
}
