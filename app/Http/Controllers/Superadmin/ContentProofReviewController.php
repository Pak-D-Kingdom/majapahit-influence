<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Endorsement\ReviewContentProofRequest;
use App\Models\ContentProof;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ContentProofReviewController extends Controller
{
    public function __construct(
        protected EndorsementService $endorsementService
    ) {}

    /**
     * Show the content proof for review.
     */
    public function show(Request $request, ContentProof $proof): View|JsonResponse
    {
        $proof->load(['endorsement.campaign.brand', 'endorsement.kolProfile.user', 'files', 'reviewer']);

        if ($request->wantsJson()) {
            return response()->json($proof);
        }

        return view('superadmin.content_proofs.show', compact('proof'));
    }

    /**
     * Review the content proof (Approve or Reject).
     */
    public function review(ReviewContentProofRequest $request, ContentProof $proof): RedirectResponse|JsonResponse
    {
        $action = $request->input('action');
        $notes = $request->input('review_notes');

        $reviewedProof = $this->endorsementService->reviewProof(
            proof: $proof,
            action: $action,
            notes: $notes,
            reviewer: Auth::user()
        );

        $message = $action === 'approve'
            ? 'Bukti konten berhasil disetujui dan komisi telah dicatat.'
            : 'Bukti konten ditolak dan permintaan revisi telah dikirimkan ke KOL.';

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'data' => $reviewedProof->load(['endorsement.commission']),
            ]);
        }

        return back()->with('success', $message);
    }
}
