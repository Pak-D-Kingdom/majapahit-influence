<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\CampaignFile;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CampaignService
{
    /**
     * Store a newly created Campaign with optional brief files.
     *
     * @param array<string, mixed> $data
     * @param array<UploadedFile> $files
     */
    public function store(array $data, array $files = [], ?User $creator = null): Campaign
    {
        $creatorId = $creator?->id ?? Auth::id();

        return DB::transaction(function () use ($data, $files, $creatorId) {
            $campaignData = [
                'brand_id' => $data['brand_id'],
                'name' => $data['name'],
                'description' => $data['description'],
                'start_date' => $data['start_date'] ?? null,
                'end_date' => $data['end_date'] ?? null,
                'budget' => $data['budget'] ?? 0,
                'content_requirements' => $data['content_requirements'] ?? null,
                'dos_and_donts' => $data['dos_and_donts'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'created_by' => $creatorId,
            ];

            $campaign = Campaign::create($campaignData);

            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $path = $file->store('campaign_briefs', 'public');
                    CampaignFile::create([
                        'campaign_id' => $campaign->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            AuditLog::log(
                action: 'create_campaign',
                entityType: 'campaign',
                entityId: $campaign->id,
                newValues: $campaign->toArray()
            );

            return $campaign;
        });
    }

    /**
     * Update an existing Campaign with optional brief files.
     *
     * @param array<string, mixed> $data
     * @param array<UploadedFile> $files
     */
    public function update(Campaign $campaign, array $data, array $files = []): Campaign
    {
        return DB::transaction(function () use ($campaign, $data, $files) {
            $oldValues = $campaign->toArray();
            $campaign->update($data);

            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $path = $file->store('campaign_briefs', 'public');
                    CampaignFile::create([
                        'campaign_id' => $campaign->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            AuditLog::log(
                action: 'update_campaign',
                entityType: 'campaign',
                entityId: $campaign->id,
                oldValues: $oldValues,
                newValues: $campaign->fresh()->toArray()
            );

            return $campaign->fresh(['brand', 'files']);
        });
    }

    /**
     * Assign active KOL to campaign (Business Rules BR2 & BR3).
     *
     * @param array<string, mixed> $data
     */
    public function assignKol(Campaign $campaign, array $data, ?User $admin = null): Endorsement
    {
        $kol = KolProfile::findOrFail($data['kol_profile_id']);

        if ($kol->status !== 'aktif') {
            throw ValidationException::withMessages([
                'kol_profile_id' => ["KOL berstatus '{$kol->status}' tidak dapat di-assign. Hanya KOL aktif yang diperbolehkan (BR2 & BR3)."],
            ]);
        }

        $adminId = $admin?->id ?? Auth::id();

        return DB::transaction(function () use ($campaign, $kol, $data, $adminId, $admin) {
            $endorsement = Endorsement::create([
                'campaign_id' => $campaign->id,
                'kol_profile_id' => $kol->id,
                'content_type' => $data['content_type'],
                'fee' => $data['fee'],
                'deadline' => $data['deadline'],
                'start_date' => $data['start_date'] ?? null,
                'status' => 'assigned',
                'assigned_by' => $adminId,
                'notes' => $data['notes'] ?? null,
            ]);

            AuditLog::log(
                action: 'assign_kol_endorsement',
                entityType: 'endorsement',
                entityId: $endorsement->id,
                newValues: $endorsement->toArray(),
                user: $admin
            );

            // In-app notification for KOL
            if ($kol->user_id) {
                Notification::create([
                    'user_id' => $kol->user_id,
                    'type' => 'new_endorsement',
                    'title' => 'Tugas Endorsement Baru',
                    'body' => "Anda telah ditugaskan untuk campaign '{$campaign->name}' ({$endorsement->content_type}). Deadline: " . date('d/m/Y', strtotime($endorsement->deadline)),
                    'target_url' => "/kol/endorsements/{$endorsement->id}",
                ]);
            }

            return $endorsement;
        });
    }
}
