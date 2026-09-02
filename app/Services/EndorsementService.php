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
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class EndorsementService
{
    /**
     * Assign an active KOL to a campaign (Business Rules BR2 & BR3).
     */
    public function assignKol(Campaign $campaign, array $data, ?User $assigner = null): Endorsement
    {
        $kol = KolProfile::findOrFail($data['kol_profile_id']);

        if ($kol->status !== 'aktif') {
            throw ValidationException::withMessages([
                'kol_profile_id' => ["KOL berstatus '{$kol->status}' tidak dapat di-assign. Hanya KOL aktif yang diperbolehkan."],
            ]);
        }

        return DB::transaction(function () use ($campaign, $kol, $data, $assigner) {
            $endorsement = Endorsement::create([
                'campaign_id' => $campaign->id,
                'kol_profile_id' => $kol->id,
                'content_type' => $data['content_type'],
                'fee' => $data['fee'],
                'deadline' => $data['deadline'],
                'start_date' => $data['start_date'] ?? null,
                'status' => 'assigned',
                'assigned_by' => $assigner?->id,
                'notes' => $data['notes'] ?? null,
            ]);

            // Audit log
            AuditLog::log(
                action: 'assign_kol_endorsement',
                entityType: 'endorsement',
                entityId: $endorsement->id,
                newValues: $endorsement->toArray(),
                user: $assigner
            );

            // In-app notification for KOL
            if ($kol->user_id) {
                Notification::create([
                    'user_id' => $kol->user_id,
                    'type' => 'new_endorsement',
                    'title' => 'Tugas Endorsement Baru',
                    'body' => "Anda telah ditugaskan untuk campaign '{$campaign->name}' ({$endorsement->content_type}). Deadline: {$endorsement->deadline->format('d/m/Y')}.",
                    'target_url' => "/kol/endorsements/{$endorsement->id}",
                ]);
            }

            return $endorsement;
        });
    }

    /**
     * Submit content proof (by KOL).
     *
     * @param array<UploadedFile> $files
     */
    public function submitProof(Endorsement $endorsement, array $data, array $files): ContentProof
    {
        return DB::transaction(function () use ($endorsement, $data, $files) {
            $proof = ContentProof::create([
                'endorsement_id' => $endorsement->id,
                'posted_at' => $data['posted_at'],
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

            return $proof;
        });
    }

    /**
     * Review submitted content proof (Approve or Reject with revision notes).
     */
    public function reviewProof(ContentProof $proof, string $action, ?string $notes = null, ?User $reviewer = null): ContentProof
    {
        return DB::transaction(function () use ($proof, $action, $notes, $reviewer) {
            $endorsement = $proof->endorsement;

            if ($action === 'approve') {
                $proof->update([
                    'review_status' => 'approved',
                    'review_notes' => $notes,
                    'reviewed_by' => $reviewer?->id,
                    'reviewed_at' => now(),
                ]);

                $endorsement->update([
                    'status' => 'selesai',
                    'completed_at' => now(),
                ]);

                // Auto-generate Commission calculation (BR1) if not already created
                if (!$endorsement->commission) {
                    $commission = Commission::calculateCommission($endorsement);
                    $commission->save();
                }

                // In-app Notification for KOL
                if ($endorsement->kolProfile?->user_id) {
                    Notification::create([
                        'user_id' => $endorsement->kolProfile->user_id,
                        'type' => 'content_approved',
                        'title' => 'Bukti Konten Disetujui',
                        'body' => "Bukti konten untuk campaign '{$endorsement->campaign?->name}' telah disetujui. Komisi telah dicatat.",
                        'target_url' => "/kol/endorsements/{$endorsement->id}",
                    ]);
                }

                AuditLog::log(
                    action: 'approve_content_proof',
                    entityType: 'content_proof',
                    entityId: $proof->id,
                    newValues: ['review_status' => 'approved', 'endorsement_status' => 'selesai'],
                    user: $reviewer
                );
            } elseif ($action === 'reject') {
                $proof->update([
                    'review_status' => 'rejected',
                    'review_notes' => $notes,
                    'reviewed_by' => $reviewer?->id,
                    'reviewed_at' => now(),
                ]);

                $endorsement->update([
                    'status' => 'content_rejected',
                ]);

                // In-app Notification for KOL (Revision Request)
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
                    user: $reviewer
                );
            }

            return $proof->fresh();
        });
    }

    /**
     * Cancel endorsement.
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
