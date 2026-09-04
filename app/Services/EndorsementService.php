<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\ContentProof;
use App\Models\ContentProofFile;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EndorsementService
{
    /**
     * Assign active KOL to a campaign (Business Rules BR2 & BR3).
     */
    public function assignKol(Campaign $campaign, array $data, ?User $assigner = null): Endorsement
    {
        return app(CampaignService::class)->assignKol($campaign, $data, $assigner);
    }

    /**
     * Submit content proof (by KOL).
     *
     * @param array<string, mixed> $data
     * @param array<UploadedFile> $files
     */
    public function submitProof(Endorsement $endorsement, array $data, array $files = []): ContentProof
    {
        return DB::transaction(function () use ($endorsement, $data, $files) {
            $postedAt = $data['posted_at'] ?? ($data['post_date'] ?? now()->toDateString());

            $proof = ContentProof::create([
                'endorsement_id' => $endorsement->id,
                'posted_at' => $postedAt,
                'post_url' => $data['post_url'],
                'notes' => $data['notes'] ?? null,
                'review_status' => 'pending',
            ]);

            foreach ($files as $file) {
                if ($file instanceof UploadedFile) {
                    $path = $file->store('content_proofs', 'public');
                    ContentProofFile::create([
                        'content_proof_id' => $proof->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            $oldStatus = $endorsement->status;
            $endorsement->update(['status' => 'content_submitted']);

            AuditLog::log(
                action: 'submit_content_proof',
                entityType: 'content_proof',
                entityId: $proof->id,
                oldValues: ['endorsement_status' => $oldStatus],
                newValues: ['endorsement_status' => 'content_submitted', 'proof_id' => $proof->id]
            );

            // Notify Admins
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                foreach ($adminRole->users as $admin) {
                    Notification::create([
                        'user_id' => $admin->id,
                        'type' => 'content_submitted',
                        'title' => 'Bukti Konten Baru Diserahkan',
                        'body' => "KOL {$endorsement->kolProfile?->nickname} telah mengunggah bukti konten untuk campaign '{$endorsement->campaign?->name}'.",
                        'target_url' => "/superadmin/endorsements/{$endorsement->id}",
                    ]);
                }
            }

            return $proof;
        });
    }

    /**
     * Review submitted content proof (Approve or Reject).
     */
    public function reviewProof(Endorsement|ContentProof $target, string $status, ?string $notes = null, ?User $admin = null): ContentProof
    {
        $proof = $target instanceof ContentProof ? $target : $target->latestContentProof;

        if (!$proof) {
            throw ValidationException::withMessages([
                'proof' => ['Bukti konten tidak ditemukan pada endorsement ini.'],
            ]);
        }

        $adminUser = $admin ?? Auth::user();

        return DB::transaction(function () use ($proof, $status, $notes, $adminUser) {
            $endorsement = $proof->endorsement;
            $normalizedStatus = in_array($status, ['approve', 'approved']) ? 'approved' : 'rejected';

            if ($normalizedStatus === 'approved') {
                $proof->update([
                    'review_status' => 'approved',
                    'review_notes' => $notes,
                    'reviewed_by' => $adminUser?->id,
                    'reviewed_at' => now(),
                ]);

                $endorsement->update([
                    'status' => 'content_approved',
                ]);

                // In-app Notification for KOL
                if ($endorsement->kolProfile?->user_id) {
                    Notification::create([
                        'user_id' => $endorsement->kolProfile->user_id,
                        'type' => 'content_approved',
                        'title' => 'Bukti Konten Disetujui',
                        'body' => "Bukti konten untuk campaign '{$endorsement->campaign?->name}' telah disetujui oleh Admin.",
                        'target_url' => "/kol/endorsements/{$endorsement->id}",
                    ]);
                }

                AuditLog::log(
                    action: 'approve_content_proof',
                    entityType: 'content_proof',
                    entityId: $proof->id,
                    newValues: ['review_status' => 'approved', 'endorsement_status' => 'content_approved'],
                    user: $adminUser
                );
            } else {
                $proof->update([
                    'review_status' => 'rejected',
                    'review_notes' => $notes,
                    'reviewed_by' => $adminUser?->id,
                    'reviewed_at' => now(),
                ]);

                $endorsement->update([
                    'status' => 'content_rejected',
                ]);

                // In-app Notification for KOL
                if ($endorsement->kolProfile?->user_id) {
                    Notification::create([
                        'user_id' => $endorsement->kolProfile->user_id,
                        'type' => 'content_rejected',
                        'title' => 'Revisi Bukti Konten Diperlukan',
                        'body' => "Bukti konten untuk campaign '{$endorsement->campaign?->name}' memerlukan revisi: {$notes}",
                        'target_url' => "/kol/endorsements/{$endorsement->id}",
                    ]);
                }

                AuditLog::log(
                    action: 'reject_content_proof',
                    entityType: 'content_proof',
                    entityId: $proof->id,
                    newValues: ['review_status' => 'rejected', 'endorsement_status' => 'content_rejected', 'notes' => $notes],
                    user: $adminUser
                );
            }

            return $proof->fresh();
        });
    }

    /**
     * Mark an endorsement as completed and auto-calculate commission (BR1).
     */
    public function markAsCompleted(Endorsement $endorsement, ?User $admin = null): Endorsement
    {
        $adminUser = $admin ?? Auth::user();

        return DB::transaction(function () use ($endorsement, $adminUser) {
            $endorsement->update([
                'status' => 'selesai',
                'completed_at' => now(),
            ]);

            // Auto-calculate Commission (BR1) if not existing
            if (!$endorsement->commission) {
                $commission = Commission::calculateCommission($endorsement);
                $commission->save();
            }

            // In-app Notification for KOL
            if ($endorsement->kolProfile?->user_id) {
                Notification::create([
                    'user_id' => $endorsement->kolProfile->user_id,
                    'type' => 'endorsement_completed',
                    'title' => 'Endorsement Selesai',
                    'body' => "Endorsement untuk campaign '{$endorsement->campaign?->name}' telah selesai. Komisi telah dicatat.",
                    'target_url' => "/kol/endorsements/{$endorsement->id}",
                ]);
            }

            AuditLog::log(
                action: 'complete_endorsement',
                entityType: 'endorsement',
                entityId: $endorsement->id,
                newValues: ['status' => 'selesai', 'completed_at' => $endorsement->completed_at],
                user: $adminUser
            );

            return $endorsement->fresh(['commission']);
        });
    }

    /**
     * Cancel an endorsement.
     */
    public function cancelEndorsement(Endorsement $endorsement, ?string $reason = null, ?User $actor = null): void
    {
        DB::transaction(function () use ($endorsement, $reason, $actor) {
            $oldStatus = $endorsement->status;
            $endorsement->update([
                'status' => 'draft',
                'notes' => $reason ? ($endorsement->notes . "\n[Dibatalkan]: " . $reason) : $endorsement->notes,
            ]);

            AuditLog::log(
                action: 'cancel_endorsement',
                entityType: 'endorsement',
                entityId: $endorsement->id,
                oldValues: ['status' => $oldStatus],
                newValues: ['status' => 'draft', 'reason' => $reason],
                user: $actor
            );
        });
    }
}
