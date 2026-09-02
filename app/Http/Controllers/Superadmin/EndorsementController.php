<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Endorsement\AssignKolRequest;
use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndorsementController extends Controller
{
    public function __construct(
        protected EndorsementService $endorsementService
    ) {}

    /**
     * Assign a KOL to a campaign.
     */
    public function store(AssignKolRequest $request, Campaign $campaign): RedirectResponse|JsonResponse
    {
        $endorsement = $this->endorsementService->assignKol(
            campaign: $campaign,
            data: $request->validated(),
            assigner: Auth::user()
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'KOL berhasil ditugaskan ke campaign.',
                'data' => $endorsement->load(['kolProfile.user', 'campaign']),
            ], 201);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)
            ->with('success', 'KOL berhasil ditugaskan ke campaign.');
    }

    /**
     * Update endorsement status manually.
     */
    public function updateStatus(Request $request, Endorsement $endorsement): RedirectResponse|JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:draft,assigned,in_progress,content_submitted,content_approved,content_rejected,selesai'],
            'notes' => ['nullable', 'string'],
        ]);

        $oldStatus = $endorsement->status;
        $endorsement->update([
            'status' => $request->input('status'),
            'notes' => $request->filled('notes') ? $request->input('notes') : $endorsement->notes,
        ]);

        AuditLog::log(
            action: 'update_endorsement_status',
            entityType: 'endorsement',
            entityId: $endorsement->id,
            oldValues: ['status' => $oldStatus],
            newValues: ['status' => $endorsement->status, 'notes' => $endorsement->notes]
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Status endorsement berhasil diperbarui.',
                'data' => $endorsement,
            ]);
        }

        return back()->with('success', 'Status endorsement berhasil diperbarui.');
    }

    /**
     * Cancel an endorsement.
     */
    public function destroy(Request $request, Endorsement $endorsement): RedirectResponse|JsonResponse
    {
        $campaign = $endorsement->campaign;
        $reason = $request->input('reason', 'Dibatalkan oleh Admin');

        $this->endorsementService->cancelEndorsement($endorsement, $reason, Auth::user());

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Penugasan endorsement berhasil dibatalkan.']);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)
            ->with('success', 'Penugasan endorsement berhasil dibatalkan.');
    }
}
