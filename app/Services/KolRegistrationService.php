<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\KolProfile;
use App\Models\KolRegistration;
use App\Models\KolSocialMedia;
use App\Models\Niche;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class KolRegistrationService
{
    /**
     * Store a new KOL registration.
     */
    public function store(array $data, array $files): KolRegistration
    {
        return DB::transaction(function () use ($data, $files) {
            $password = ! empty($data['password'])
                ? (Hash::needsRehash($data['password']) ? Hash::make($data['password']) : $data['password'])
                : null;

            $registration = KolRegistration::create([
                'registration_number' => KolRegistration::generateRegistrationNumber(),
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'password' => $password,
                'phone' => $data['phone'],
                'city' => $data['city'] ?? null,
                'niches' => $data['niches'],
                'social_media' => $data['social_media'],
                'expected_rate' => $data['expected_rate'] ?? null,
                'join_reason' => $data['join_reason'],
                'status' => 'pending_review',
            ]);

            // Save files
            foreach ($files as $file) {
                $filePath = $file->store("registrations/{$registration->id}");

                $registration->files()->create([
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                ]);
            }

            return $registration;
        });
    }

    /**
     * Approve a KOL registration.
     */
    public function approve(KolRegistration $registration, User $superadmin, array $data): User
    {
        return DB::transaction(function () use ($registration, $superadmin, $data) {
            // Update registration status
            $registration->update([
                'status' => 'approved',
                'approved_by' => $superadmin->id,
                'approved_at' => now(),
                'notes' => $data['notes'] ?? null,
            ]);

            // Create or retrieve User using password set during registration (or fallback to 'password')
            $initialPassword = ! empty($registration->password)
                ? $registration->password
                : Hash::make('password');

            $user = User::firstOrCreate(
                ['email' => $registration->email],
                [
                    'name' => $registration->full_name,
                    'password' => $initialPassword,
                ]
            );

            if ($user->wasRecentlyCreated && ! empty($registration->password)) {
                $user->password = $registration->password;
                $user->save();
            }

            $user->assignRole('kol');

            // Normalize and parse Social Media Data
            $socialMediaData = $this->normalizeSocialMedia($registration->social_media);
            $maxFollowers = 0;
            foreach ($socialMediaData as $sm) {
                if (($sm['followers_count'] ?? 0) > $maxFollowers) {
                    $maxFollowers = (int) $sm['followers_count'];
                }
            }

            $tierId = $data['tier_id'] ?? $this->determineTier($maxFollowers)?->id;

            // Create or update KolProfile
            $profile = KolProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nickname' => explode(' ', $registration->full_name)[0],
                    'city' => $registration->city,
                    'tier_id' => $tierId,
                    'status' => 'aktif',
                    'joined_at' => now(),
                ]
            );

            // Assign Niches
            $nicheNames = $this->normalizeNiches($registration->niches);
            $nicheIds = Niche::whereIn('name', $nicheNames)
                ->orWhereIn('id', $nicheNames)
                ->pluck('id')
                ->toArray();
            if (! empty($nicheIds)) {
                $profile->niches()->sync($nicheIds);
            }

            // Migrate Social Media
            $profile->socialMedia()->delete();
            foreach ($socialMediaData as $sm) {
                if (! empty($sm['username'])) {
                    KolSocialMedia::create([
                        'kol_profile_id' => $profile->id,
                        'platform' => $sm['platform'] ?? 'instagram',
                        'username' => $sm['username'],
                        'profile_url' => $sm['profile_url'] ?? null,
                        'followers_count' => (int) ($sm['followers_count'] ?? 0),
                    ]);
                }
            }

            // Audit log
            AuditLog::log(
                'kol_registration_approved',
                'kol_registration',
                $registration->id,
                ['status' => 'pending_review'],
                ['status' => 'approved', 'user_id' => $user->id],
                $superadmin
            );

            return $user;
        });
    }

    /**
     * Reject a KOL registration.
     */
    public function reject(KolRegistration $registration, User $superadmin, string $reason): void
    {
        DB::transaction(function () use ($registration, $superadmin, $reason) {
            // Log audit before deleting
            AuditLog::log(
                'kol_registration_rejected',
                'kol_registration',
                $registration->id,
                [
                    'name' => $registration->full_name,
                    'email' => $registration->email,
                ],
                ['rejection_reason' => $reason],
                $superadmin
            );

            // Delete files physically
            Storage::deleteDirectory("registrations/{$registration->id}");

            // Hard delete related data and the registration itself
            $registration->files()->delete();
            $registration->delete(); // This is a hard delete as Model doesn't use SoftDeletes
        });
    }

    /**
     * Determine Tier based on max followers.
     */
    public function determineTier(int $maxFollowers): ?Tier
    {
        return Tier::where(function ($query) use ($maxFollowers) {
            $query->where('min_followers', '<=', $maxFollowers)
                ->where(function ($q) use ($maxFollowers) {
                    $q->whereNull('max_followers')
                        ->orWhere('max_followers', '>=', $maxFollowers);
                });
        })
            ->orderBy('min_followers', 'desc')
            ->first();
    }

    /**
     * Normalize social media data to a standardized array of items.
     *
     * @return array<int, array{platform: string, username: string, profile_url: ?string, followers_count: int}>
     */
    public function normalizeSocialMedia(mixed $data): array
    {
        if (empty($data)) {
            return [];
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = is_array($decoded) ? $decoded : ['username' => $data, 'platform' => 'instagram'];
        }

        if (! is_array($data)) {
            return [];
        }

        // If it's a single associative array with 'platform' or 'username'
        if (isset($data['platform']) || isset($data['username'])) {
            return [[
                'platform' => (string) ($data['platform'] ?? 'instagram'),
                'username' => (string) ($data['username'] ?? ''),
                'profile_url' => $data['profile_url'] ?? null,
                'followers_count' => (int) ($data['followers_count'] ?? 0),
            ]];
        }

        $normalized = [];
        foreach ($data as $key => $item) {
            if (is_array($item)) {
                $normalized[] = [
                    'platform' => (string) ($item['platform'] ?? (is_string($key) ? $key : 'instagram')),
                    'username' => (string) ($item['username'] ?? ($item['handle'] ?? '')),
                    'profile_url' => $item['profile_url'] ?? null,
                    'followers_count' => (int) ($item['followers_count'] ?? 0),
                ];
            } elseif (is_string($item) && ! empty($item)) {
                $normalized[] = [
                    'platform' => is_string($key) && ! in_array($key, ['platform', 'username', 'profile_url', 'followers_count']) ? $key : 'instagram',
                    'username' => $item,
                    'profile_url' => null,
                    'followers_count' => 0,
                ];
            }
        }

        return $normalized;
    }

    /**
     * Normalize niches to an array of names/IDs.
     *
     * @return array<int, string|int>
     */
    public function normalizeNiches(mixed $data): array
    {
        if (empty($data)) {
            return [];
        }

        if (is_string($data)) {
            $decoded = json_decode($data, true);
            $data = is_array($decoded) ? $decoded : [$data];
        }

        if (! is_array($data)) {
            return [];
        }

        return array_values(array_filter($data));
    }
}
