<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\Commission;
use App\Models\CommissionApproval;
use App\Models\Endorsement;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommissionService
{
    /**
     * Calculate and create commission record when endorsement is completed.
     */
    public function calculateAndCreate(Endorsement $endorsement, ?float $overridePct = null, ?string $overrideReason = null): Commission
    {
        return DB::transaction(function () use ($endorsement, $overridePct, $overrideReason) {
            $kol = $endorsement->kolProfile;
            $fee = (float) $endorsement->fee;

            if (!is_null($overridePct)) {
                $pct = $overridePct;
                $isOverride = true;
            } else {
                $pct = $kol ? $kol->effective_commission_pct : 60.00;
                $isOverride = $kol ? !is_null($kol->commission_override_pct) : false;
            }

            $commissionAmount = $fee * ($pct / 100);
            $agencyAmount = $fee - $commissionAmount;

            $commission = Commission::updateOrCreate(
                ['endorsement_id' => $endorsement->id],
                [
                    'kol_profile_id' => $endorsement->kol_profile_id,
                    'endorsement_fee' => $fee,
                    'commission_pct' => $pct,
                    'commission_amount' => $commissionAmount,
                    'agency_amount' => $agencyAmount,
                    'is_override' => $isOverride,
                    'override_reason' => $overrideReason ?? ($kol?->status_reason),
                    'status' => 'pending',
                ]
            );

            AuditLog::log(
                action: 'create_commission',
                entityType: Commission::class,
                entityId: $commission->id,
                newValues: $commission->toArray()
            );

            return $commission;
        });
    }

    /**
     * Request disbursement for given commission IDs (by KOL or Admin).
     */
    public function requestDisbursement(array $commissionIds, User $requester, ?string $notes = null): int
    {
        return DB::transaction(function () use ($commissionIds, $requester, $notes) {
            $query = Commission::whereIn('id', $commissionIds);

            // If requester is a KOL, only allow requesting their own commissions
            if ($requester->isKol() && $requester->kolProfile) {
                $query->where('kol_profile_id', $requester->kolProfile->id);
            }

            // Only commissions in 'pending' or 'rejected' status can be requested
            $commissions = $query->whereIn('status', ['pending', 'rejected'])->get();

            if ($commissions->isEmpty()) {
                return 0;
            }

            $updatedCount = 0;
            foreach ($commissions as $commission) {
                $oldStatus = $commission->status;
                $commission->status = 'pending_review';
                $commission->save();

                CommissionApproval::create([
                    'commission_id' => $commission->id,
                    'action' => 'request',
                    'performed_by' => $requester->id,
                    'notes' => $notes,
                ]);

                AuditLog::log(
                    action: 'request_disbursement',
                    entityType: Commission::class,
                    entityId: $commission->id,
                    oldValues: ['status' => $oldStatus],
                    newValues: ['status' => 'pending_review', 'notes' => $notes],
                    user: $requester
                );

                $updatedCount++;
            }

            // Notify all Admin users
            $admins = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })->get();

            $requesterName = $requester->name;
            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'type' => 'commission_disbursement_request',
                    'title' => 'Pengajuan Pencairan Komisi',
                    'body' => "Terdapat {$updatedCount} pengajuan pencairan komisi baru dari {$requesterName}.",
                    'target_url' => route('superadmin.commissions.index', ['status' => 'pending_review']),
                ]);
            }

            return $updatedCount;
        });
    }

    /**
     * Approve or Reject disbursement requests (Batch or Single).
     */
    public function approveDisbursement(array $commissionIds, string $status, ?string $notes, User $admin): int
    {
        return DB::transaction(function () use ($commissionIds, $status, $notes, $admin) {
            $commissions = Commission::with(['kolProfile.user'])
                ->whereIn('id', $commissionIds)
                ->whereIn('status', ['pending', 'pending_review'])
                ->get();

            if ($commissions->isEmpty()) {
                return 0;
            }

            $processedCount = 0;
            foreach ($commissions as $commission) {
                $oldStatus = $commission->status;
                $commission->status = $status; // 'approved' or 'rejected'
                $commission->save();

                CommissionApproval::create([
                    'commission_id' => $commission->id,
                    'action' => $status,
                    'performed_by' => $admin->id,
                    'notes' => $notes,
                ]);

                AuditLog::log(
                    action: "{$status}_disbursement",
                    entityType: Commission::class,
                    entityId: $commission->id,
                    oldValues: ['status' => $oldStatus],
                    newValues: ['status' => $status, 'notes' => $notes],
                    user: $admin
                );

                // Notify KOL
                $kolUser = $commission->kolProfile?->user;
                if ($kolUser) {
                    $statusLabel = $status === 'approved' ? 'disetujui' : 'ditolak';
                    Notification::create([
                        'user_id' => $kolUser->id,
                        'type' => 'commission_status_updated',
                        'title' => "Pengajuan Komisi " . ucfirst($statusLabel),
                        'body' => "Pengajuan pencairan komisi Anda sebesar Rp " . number_format($commission->commission_amount, 0, ',', '.') . " telah {$statusLabel}." . ($notes ? " Catatan: {$notes}" : ''),
                        'target_url' => route('kol.commissions.index'),
                    ]);
                }

                $processedCount++;
            }

            return $processedCount;
        });
    }

    /**
     * Mark commission as disbursed, storing proof and setting date.
     */
    public function markAsDisbursed(Commission $commission, array $data, UploadedFile $file, User $admin): Commission
    {
        return DB::transaction(function () use ($commission, $data, $file, $admin) {
            $oldStatus = $commission->status;

            // Store proof file in public disk
            $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
            $proofPath = $file->storeAs("disbursements/{$commission->id}", $filename, 'public');

            $commission->status = 'dicairkan';
            $commission->disbursed_at = $data['transfer_date'];
            $commission->disbursement_proof_path = $proofPath;
            $commission->save();

            CommissionApproval::create([
                'commission_id' => $commission->id,
                'action' => 'disburse',
                'performed_by' => $admin->id,
                'notes' => $data['notes'] ?? null,
                'proof_path' => $proofPath,
            ]);

            AuditLog::log(
                action: 'disburse_commission',
                entityType: Commission::class,
                entityId: $commission->id,
                oldValues: ['status' => $oldStatus],
                newValues: [
                    'status' => 'dicairkan',
                    'disbursed_at' => $data['transfer_date'],
                    'disbursement_proof_path' => $proofPath,
                    'notes' => $data['notes'] ?? null,
                ],
                user: $admin
            );

            // Notify KOL
            $kolUser = $commission->kolProfile?->user;
            if ($kolUser) {
                Notification::create([
                    'user_id' => $kolUser->id,
                    'type' => 'commission_disbursed',
                    'title' => 'Komisi Telah Dicairkan',
                    'body' => 'Komisi sebesar Rp ' . number_format($commission->commission_amount, 0, ',', '.') . ' telah berhasil ditransfer ke rekening Anda.',
                    'target_url' => route('kol.commissions.index'),
                ]);
            }

            return $commission;
        });
    }
}
