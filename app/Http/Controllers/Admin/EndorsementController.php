<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignKolRequest;
use App\Http\Requests\Admin\ReviewContentProofRequest;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Models\User;
use App\Services\CampaignService;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
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
    public function assign(Campaign $campaign, AssignKolRequest $request): RedirectResponse|JsonResponse
    {
        $admin = Auth::user() ?? User::first();

        $endorsement = $this->campaignService->assignKol(
            campaign: $campaign,
            data: $request->validated(),
            admin: $admin
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'KOL berhasil ditugaskan ke campaign.',
                'data' => $endorsement->load(['kolProfile.user', 'campaign']),
            ], 201);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)->with('success', 'KOL berhasil ditugaskan ke campaign.');
    }

    /**
     * Review submitted content proof (POST /superadmin/endorsements/{endorsement}/review).
     */
    public function reviewProof(Endorsement $endorsement, ReviewContentProofRequest $request): RedirectResponse|JsonResponse
    {
        $admin = Auth::user() ?? User::first();
        $status = $request->input('status') ?? ($request->input('action') === 'approve' ? 'approved' : 'rejected');
        $notes = $request->input('notes') ?? $request->input('review_notes');

        $proof = $this->endorsementService->reviewProof(
            target: $endorsement,
            status: $status,
            notes: $notes,
            admin: $admin
        );

        $message = $status === 'approved'
            ? 'Bukti konten berhasil disetujui.'
            : 'Bukti konten ditolak dan catatan revisi telah dikirim ke KOL.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'data' => $proof->load('endorsement'),
            ]);
        }

        return redirect()->route('superadmin.endorsements.show', $endorsement)->with('success', $message);
    }

    /**
     * Mark an endorsement as completed (POST /superadmin/endorsements/{endorsement}/complete).
     */
    public function complete(Request $request, Endorsement $endorsement): RedirectResponse|JsonResponse
    {
        $admin = Auth::user() ?? User::first();

        $completedEndorsement = $this->endorsementService->markAsCompleted(
            endorsement: $endorsement,
            admin: $admin
        );

        $message = 'Endorsement berhasil diselesaikan dan komisi telah dicatat.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'data' => $completedEndorsement->load('commission'),
            ]);
        }

        return redirect()->route('superadmin.endorsements.show', $endorsement)->with('success', $message);
    }

    /**
     * Cancel an endorsement assignment.
     */
    public function destroy(Request $request, Endorsement $endorsement): RedirectResponse|JsonResponse
    {
        $reason = $request->input('reason', 'Dibatalkan oleh Admin');

        $this->endorsementService->cancelEndorsement($endorsement, $reason, Auth::user());

        $message = 'Penugasan endorsement berhasil dibatalkan.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
            ]);
        }

        return redirect()->route('superadmin.endorsements.index')->with('success', $message);
    }
}
