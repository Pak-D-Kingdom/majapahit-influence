<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\KolProfile;
use App\Models\KolRegistration;
use App\Models\KolSocialMedia;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class KolRegistrationService
{
    /**
     * Store a new KOL registration.
     */
    public function store(array $data, array $files): KolRegistration
    {
        return DB::transaction(function () use ($data, $files) {
            $registration = KolRegistration::create([
                'registration_number' => KolRegistration::generateRegistrationNumber(),
                'full_name'           => $data['full_name'],
                'email'               => $data['email'],
                'phone'               => $data['phone'],
                'city'                => $data['city'] ?? null,
                'niches'              => $data['niches'],
                'social_media'        => $data['social_media'],
                'expected_rate'       => $data['expected_rate'] ?? null,
                'join_reason'         => $data['join_reason'],
                'status'              => 'pending_review',
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
                'status'      => 'approved',
                'approved_by' => $superadmin->id,
                'approved_at' => now(),
                'notes'       => $data['notes'] ?? null,
            ]);

            // Create User
            $password = Str::random(12);
            $user = User::create([
                'name'      => $registration->full_name,
                'email'     => $registration->email,
                'password'  => \Illuminate\Support\Facades\Hash::make($password),
            ]);
            $user->assignRole('kol');

            // Determine max followers to find Tier if not provided
            $socialMediaData = $registration->social_media ?? [];
            $maxFollowers = 0;
            foreach ($socialMediaData as $sm) {
                if (($sm['followers_count'] ?? 0) > $maxFollowers) {
                    $maxFollowers = (int) $sm['followers_count'];
                }
            }

            $tierId = $data['tier_id'] ?? $this->determineTier($maxFollowers)?->id;

            // Create KolProfile
            $profile = KolProfile::create([
                'user_id'   => $user->id,
                'nickname'  => explode(' ', $registration->full_name)[0],
                'city'      => $registration->city,
                'tier_id'   => $tierId,
                'status'    => 'aktif',
                'joined_at' => now(),
            ]);

            // Assign Niches
            $nicheNames = $registration->niches ?? [];
            $nicheIds = \App\Models\Niche::whereIn('name', $nicheNames)->pluck('id')->toArray();
            $profile->niches()->sync($nicheIds);

            // Migrate Social Media
            foreach ($socialMediaData as $sm) {
                KolSocialMedia::create([
                    'kol_profile_id'  => $profile->id,
                    'platform'        => $sm['platform'],
                    'username'        => $sm['username'],
                    'profile_url'     => $sm['profile_url'] ?? null,
                    'followers_count' => $sm['followers_count'] ?? 0,
                ]);
            }

            // Move files from temp registration to profile if needed (for now, just keeping them in DB)
            // (Files are portfolio, usually kept in registration for audit)
            // But if there's photo profile, we would move it.

            // Audit log
            AuditLog::log(
                'kol_registration_approved', 
                'kol_registration', 
                $registration->id, 
                ['status' => 'pending_review'], 
                ['status' => 'approved', 'user_id' => $user->id], 
                $superadmin
            );

            // TODO: Notify Dev 5 for sending email credential to $registration->email with $password

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
                    'email' => $registration->email
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
}
