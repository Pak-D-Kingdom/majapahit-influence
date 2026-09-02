<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\KolProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KolProfileService
{
    /**
     * Update KolProfile data including related arrays (social media, rate cards).
     */
    public function updateProfile(KolProfile $profile, array $data, ?User $actor = null): KolProfile
    {
        return DB::transaction(function () use ($profile, $data, $actor) {
            $oldValues = $profile->toArray();

            // Handle photo upload
            if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                if ($profile->photo_path) {
                    Storage::disk('public')->delete($profile->photo_path);
                }
                $data['photo_path'] = $data['photo']->store("profiles/{$profile->id}", 'public');
            }

            // Update basic profile
            $profile->update([
                'nickname'            => $data['nickname'] ?? $profile->nickname,
                'bio'                 => $data['bio'] ?? $profile->bio,
                'city'                => $data['city'] ?? $profile->city,
                'province'            => $data['province'] ?? $profile->province,
                'photo_path'          => $data['photo_path'] ?? $profile->photo_path,
                'bank_name'           => $data['bank_name'] ?? $profile->bank_name,
                'bank_account_number' => $data['bank_account_number'] ?? $profile->bank_account_number,
                'bank_account_name'   => $data['bank_account_name'] ?? $profile->bank_account_name,
            ]);

            // Sync Social Media
            if (isset($data['social_media']) && is_array($data['social_media'])) {
                $profile->socialMedia()->delete(); // Wipe old ones
                $profile->socialMedia()->createMany($data['social_media']);
            }

            // Sync Rate Cards
            if (isset($data['rate_cards']) && is_array($data['rate_cards'])) {
                $profile->rateCards()->delete(); // Wipe old ones
                $profile->rateCards()->createMany($data['rate_cards']);
            }

            // Log Audit
            if ($actor) {
                AuditLog::log(
                    'kol_profile_updated',
                    'kol_profile',
                    $profile->id,
                    $oldValues,
                    $profile->toArray(),
                    $actor
                );
            }

            return $profile;
        });
    }

    /**
     * Change KolProfile status (Aktif, Nonaktif, Blacklist).
     */
    public function changeStatus(KolProfile $profile, string $newStatus, ?string $reason, User $admin): void
    {
        $oldStatus = $profile->status;

        // Validation rule for state machine
        if ($oldStatus === 'blacklist') {
            throw new \InvalidArgumentException("Status 'blacklist' tidak dapat diubah (irreversible).");
        }

        if (in_array($newStatus, ['nonaktif', 'blacklist']) && empty($reason)) {
            throw new \InvalidArgumentException("Alasan wajib diisi untuk mengubah status menjadi '{$newStatus}'.");
        }

        DB::transaction(function () use ($profile, $oldStatus, $newStatus, $reason, $admin) {
            $profile->update([
                'status' => $newStatus,
                'status_reason' => $reason,
            ]);

            AuditLog::log(
                'kol_status_changed',
                'kol_profile',
                $profile->id,
                ['status' => $oldStatus],
                ['status' => $newStatus, 'reason' => $reason],
                $admin
            );
        });
    }

    /**
     * Filter KOL profiles for Admin list.
     */
    public function filter(array $filters): Builder
    {
        $query = KolProfile::with(['user', 'tier', 'niches', 'socialMedia']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhere('nickname', 'like', "%{$search}%")
                  ->orWhereHas('socialMedia', fn ($s) => $s->where('username', 'like', "%{$search}%"));
            });
        }

        if (!empty($filters['niches'])) {
            $query->whereHas('niches', fn ($q) => $q->whereIn('niches.id', $filters['niches']));
        }

        if (!empty($filters['platforms'])) {
            $query->whereHas('socialMedia', fn ($q) => $q->whereIn('platform', $filters['platforms']));
        }

        if (!empty($filters['followers_min'])) {
            $query->whereHas('socialMedia', fn ($q) => $q->where('followers_count', '>=', $filters['followers_min']));
        }

        if (!empty($filters['followers_max'])) {
            $query->whereHas('socialMedia', fn ($q) => $q->where('followers_count', '<=', $filters['followers_max']));
        }

        if (!empty($filters['tier_id'])) {
            $query->where('tier_id', $filters['tier_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['city'])) {
            $query->where('city', 'like', "%{$filters['city']}%");
        }

        return $query;
    }
}
