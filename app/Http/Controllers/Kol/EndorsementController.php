<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\StoreContentProofRequest;
use App\Models\Endorsement;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EndorsementController extends Controller
{
    public function __construct(
        protected EndorsementService $endorsementService
    ) {}

    /**
     * Display a listing of endorsements for the logged-in KOL (Tabs: Aktif, Mendatang, Riwayat).
     */
    public function index(Request $request): JsonResponse|View
    {
        $kolProfile = Auth::user()?->kolProfile ?? \App\Models\KolProfile::first();

        if (!$kolProfile) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Profil KOL tidak ditemukan.'], 404);
            }
            abort(404, 'Profil KOL tidak ditemukan.');
        }

        $tab = $request->input('tab', 'active');
        $query = Endorsement::where('kol_profile_id', $kolProfile->id)
            ->with(['campaign.brand', 'latestContentProof.files', 'commission']);

        if ($tab === 'active' || $tab === 'aktif') {
            $query->whereIn('status', ['assigned', 'in_progress', 'content_submitted', 'content_rejected']);
        } elseif ($tab === 'upcoming' || $tab === 'mendatang') {
            $query->where('status', 'assigned')->where('start_date', '>', now());
        } elseif ($tab === 'history' || $tab === 'riwayat') {
            $query->whereIn('status', ['content_approved', 'selesai']);
        }

        $endorsements = $query->orderBy('deadline')->paginate($request->input('per_page', 10))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($endorsements);
        }

        if (view()->exists('kol.endorsements.index')) {
            return view('kol.endorsements.index', compact('endorsements', 'tab'));
        }

        return response()->json($endorsements);
    }

    /**
     * Display the specified endorsement detail and brief.
     */
    public function show(Request $request, Endorsement $endorsement): JsonResponse|View
    {
        $endorsement->load([
            'campaign.brand',
            'campaign.files',
            'contentProofs.files',
            'commission',
        ]);

        if ($request->wantsJson()) {
            return response()->json($endorsement);
        }

        if (view()->exists('kol.endorsements.show')) {
            return view('kol.endorsements.show', compact('endorsement'));
        }

        return response()->json($endorsement);
    }

    /**
     * Upload content proof for the endorsement (POST /kol/endorsements/{id}/upload-proof).
     */
    public function uploadProof(Endorsement $endorsement, StoreContentProofRequest $request): JsonResponse
    {
        $proof = $this->endorsementService->submitProof(
            endorsement: $endorsement,
            data: $request->validated(),
            files: $request->file('proof_files', [])
        );

        return response()->json([
            'message' => 'Bukti konten berhasil diunggah dan sedang ditinjau oleh Admin.',
            'data' => $proof->load('files'),
        ], 201);
    }
}
