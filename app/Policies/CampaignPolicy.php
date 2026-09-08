<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isSuperadmin() || $user->hasRole('kol');
    }

    public function view(User $user, Campaign $campaign): bool
    {
        return $user->isSuperadmin()
            || $campaign->endorsements()->whereHas('kolProfile', fn ($query) => $query->where('user_id', $user->id))->exists();
    }

    public function create(User $user): bool
    {
        return $user->isSuperadmin();
    }

    public function update(User $user, Campaign $campaign): bool
    {
        return $user->isSuperadmin();
    }

    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->isSuperadmin();
    }
}
