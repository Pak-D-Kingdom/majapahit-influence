<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\RequestDisbursementRequest;
use App\Models\Commission;
use App\Models\KolProfile;
use App\Services\CommissionService;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function __construct(
        protected CommissionService $commissionService
    ) {}

    /**
     * Display a listing of commissions for the authenticated KOL.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $kolProfile = $user?->kolProfile ?? KolProfile::with('user', 'tier')->first();

        if (!$kolProfile) {
            return view('kol.commissions.index', [
                'kolProfile' => null,
                'commissions' => collect(),
                'stats' => [
                    'total_all_time' => 0,
                    'total_pending' => 0,
                    'total_disbursed' => 0,
                    'total_approved' => 0,
                ],
            ]);
        }

        $query = Commission::with(['endorsement.campaign.brand', 'approvals'])
            ->where('kol_profile_id', $kolProfile->id);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Summary statistics for this KOL
        $stats = [
            'total_all_time' => Commission::where('kol_profile_id', $kolProfile->id)->sum('commission_amount'),
            'total_pending' => Commission::where('kol_profile_id', $kolProfile->id)->whereIn('status', ['pending', 'pending_review'])->sum('commission_amount'),
            'total_approved' => Commission::where('kol_profile_id', $kolProfile->id)->where('status', 'approved')->sum('commission_amount'),
            'total_disbursed' => Commission::where('kol_profile_id', $kolProfile->id)->where('status', 'dicairkan')->sum('commission_amount'),
        ];

        $commissions = $query->latest('id')->paginate(15)->withQueryString();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'kol_profile' => $kolProfile,
                'stats' => $stats,
                'data' => $commissions,
            ]);
        }

        return view('kol.commissions.index', compact('kolProfile', 'commissions', 'stats'));
    }

    /**
     * Request disbursement for selected commissions.
     */
    public function requestDisbursement(RequestDisbursementRequest $request)
    {
        $user = auth()->user() ?? \App\Models\User::whereHas('kolProfile')->first();

        if (!$user) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Profil KOL tidak ditemukan.'], 404);
            }
            return redirect()->back()->with('error', 'Profil KOL tidak ditemukan.');
        }

        $count = $this->commissionService->requestDisbursement(
            $request->validated('commission_ids'),
            $user,
            $request->validated('notes')
        );

        if ($count === 0) {
            if ($request->wantsJson()) {
                return response()->json(['status' => 'error', 'message' => 'Tidak ada komisi valid yang dapat diajukan.'], 422);
            }
            return redirect()->back()->with('error', 'Tidak ada komisi valid yang dapat diajukan.');
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Berhasil mengajukan pencairan untuk {$count} komisi. Menunggu persetujuan Admin.",
                'requested_count' => $count,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', "Berhasil mengajukan pencairan untuk {$count} komisi. Menunggu persetujuan Admin.");
    }
}
