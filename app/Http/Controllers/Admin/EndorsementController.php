<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignKolRequest;
use App\Http\Requests\Admin\ReviewContentProofRequest;
use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Services\CampaignService;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndorsementController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService,
        protected EndorsementService $endorsementService
    ) {}

    /**
     * Assign a KOL to a campaign (POST /superadmin/campaigns/{campaign}/assign).
     */
    public function assign(Campaign $campaign, AssignKolRequest $request): JsonResponse
    {
        $endorsement = $this->campaignService->assignKol(
            campaign: $campaign,
            data: $request->validated(),
            admin: Auth::user()
        );

        return response()->json([
            'message' => 'KOL berhasil ditugaskan ke campaign.',
            'data' => $endorsement->load(['kolProfile.user', 'campaign']),
        ], 201);
    }

    /**
     * Review submitted content proof (POST /superadmin/endorsements/{endorsement}/review).
     */
    public function reviewProof(Endorsement $endorsement, ReviewContentProofRequest $request): JsonResponse
    {
        $status = $request->input('status') ?? ($request->input('action') === 'approve' ? 'approved' : 'rejected');
        $notes = $request->input('notes') ?? $request->input('review_notes');

        $proof = $this->endorsementService->reviewProof(
            target: $endorsement,
            status: $status,
            notes: $notes,
            admin: Auth::user()
        );

        $message = $status === 'approved'
            ? 'Bukti konten berhasil disetujui.'
            : 'Bukti konten ditolak dan catatan revisi telah dikirim ke KOL.';

        return response()->json([
            'message' => $message,
            'data' => $proof->load('endorsement'),
        ]);
    }

    /**
     * Mark an endorsement as completed (POST /superadmin/endorsements/{endorsement}/complete).
     */
    public function complete(Endorsement $endorsement): JsonResponse
    {
        $completedEndorsement = $this->endorsementService->markAsCompleted(
            endorsement: $endorsement,
            admin: Auth::user()
        );

        return response()->json([
            'message' => 'Endorsement berhasil diselesaikan dan komisi telah dicatat.',
            'data' => $completedEndorsement->load('commission'),
        ]);
    }

    /**
     * Cancel an endorsement assignment.
     */
    public function destroy(Request $request, Endorsement $endorsement): JsonResponse
    {
        $reason = $request->input('reason', 'Dibatalkan oleh Admin');

        $this->endorsementService->cancelEndorsement($endorsement, $reason, Auth::user());

        return response()->json([
            'message' => 'Penugasan endorsement berhasil dibatalkan.',
        ]);
    }
}
