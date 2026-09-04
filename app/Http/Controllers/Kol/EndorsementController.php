<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\StoreContentProofRequest;
use App\Models\Endorsement;
use App\Services\EndorsementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndorsementController extends Controller
{
    public function __construct(
        protected EndorsementService $endorsementService
    ) {}

    /**
     * Display a listing of endorsements for the logged-in KOL (Tabs: Aktif, Mendatang, Riwayat).
     */
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        $kolProfile = $user->kolProfile;

        if (!$kolProfile) {
            return response()->json(['message' => 'Profil KOL tidak ditemukan.'], 404);
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

        $endorsements = $query->orderBy('deadline')->paginate($request->input('per_page', 10));

        return response()->json($endorsements);
    }

    /**
     * Display the specified endorsement detail and brief.
     */
    public function show(Request $request, Endorsement $endorsement): JsonResponse
    {
        $user = Auth::user();
        $kolProfile = $user->kolProfile;

        if ($kolProfile && $endorsement->kol_profile_id !== $kolProfile->id && !$user->isAdmin()) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke endorsement ini.'], 403);
        }

        $endorsement->load([
            'campaign.brand',
            'campaign.files',
            'contentProofs.files',
            'commission',
        ]);

        return response()->json($endorsement);
    }

    /**
     * Upload content proof for the endorsement (POST /kol/endorsements/{id}/upload-proof).
     */
    public function uploadProof(Endorsement $endorsement, StoreContentProofRequest $request): JsonResponse
    {
        $user = Auth::user();
        $kolProfile = $user->kolProfile;

        if ($kolProfile && $endorsement->kol_profile_id !== $kolProfile->id && !$user->isAdmin()) {
            return response()->json(['message' => 'Anda tidak memiliki akses ke endorsement ini.'], 403);
        }

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
