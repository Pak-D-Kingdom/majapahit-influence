<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Endorsement\SubmitContentProofRequest;
use App\Models\Endorsement;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KolEndorsementController extends Controller
{
    public function __construct(
        protected EndorsementService $endorsementService
    ) {}

    /**
     * Display a listing of endorsements assigned to the current KOL.
     */
    public function index(Request $request): View|JsonResponse
    {
        $user = Auth::user();
        $kolProfile = $user->kolProfile;

        if (!$kolProfile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Profil KOL tidak ditemukan.'], 404);
            }
            abort(404, 'Profil KOL tidak ditemukan.');
        }

        $query = Endorsement::where('kol_profile_id', $kolProfile->id)
            ->with(['campaign.brand', 'latestContentProof.files', 'commission']);

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->whereIn('status', ['assigned', 'in_progress', 'content_rejected']);
            } elseif ($status === 'submitted') {
                $query->where('status', 'content_submitted');
            } elseif ($status === 'completed') {
                $query->whereIn('status', ['content_approved', 'selesai']);
            } else {
                $query->where('status', $status);
            }
        }

        $endorsements = $query->orderBy('deadline')->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return response()->json($endorsements);
        }

        return view('kol.endorsements.index', compact('endorsements'));
    }

    /**
     * Display the specified endorsement details and campaign brief.
     */
    public function show(Request $request, Endorsement $endorsement): View|JsonResponse
    {
        $user = Auth::user();
        $kolProfile = $user->kolProfile;

        // Authorize KOL owns this endorsement (unless admin)
        if ($kolProfile && $endorsement->kol_profile_id !== $kolProfile->id && !$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke endorsement ini.');
        }

        $endorsement->load([
            'campaign.brand',
            'campaign.files',
            'contentProofs.files',
            'commission',
        ]);

        if ($request->wantsJson()) {
            return response()->json($endorsement);
        }

        return view('kol.endorsements.show', compact('endorsement'));
    }

    /**
     * Submit content proof for the endorsement.
     */
    public function submitProof(SubmitContentProofRequest $request, Endorsement $endorsement): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        $kolProfile = $user->kolProfile;

        if ($kolProfile && $endorsement->kol_profile_id !== $kolProfile->id && !$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke endorsement ini.');
        }

        $proof = $this->endorsementService->submitProof(
            endorsement: $endorsement,
            data: $request->validated(),
            files: $request->file('proof_files', [])
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Bukti konten berhasil diunggah dan sedang ditinjau oleh Admin.',
                'data' => $proof->load('files'),
            ], 201);
        }

        return redirect()->route('kol.endorsements.show', $endorsement)
            ->with('success', 'Bukti konten berhasil diunggah dan sedang ditinjau oleh Admin.');
    }
}
