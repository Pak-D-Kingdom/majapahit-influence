<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\RequestDisbursementRequest;
use App\Models\Commission;
use App\Models\KolProfile;
use App\Models\User;
use App\Services\AuditLogService;
use App\Services\CommissionService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        if (! $kolProfile) {
            return view('kol.commissions.index', [
                'kolProfile' => null,
                'commissions' => collect(),
                'stats' => [
                    'total_all_time' => 0,
                    'total_pending' => 0,
                    'total_disbursed' => 0,
                    'total_approved' => 0,
                    'month' => 0,
                    'pending' => 0,
                    'disbursed' => 0,
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
            'month' => Commission::where('kol_profile_id', $kolProfile->id)->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount'),
            'pending' => Commission::where('kol_profile_id', $kolProfile->id)->whereIn('status', ['pending', 'approved', 'pending_review'])->sum('commission_amount'),
            'disbursed' => Commission::where('kol_profile_id', $kolProfile->id)->where('status', 'dicairkan')->sum('commission_amount'),
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
     * Display the specified commission detail.
     */
    public function show(Request $request, Commission $commission)
    {
        $this->authorize('view', $commission);

        $commission->load(['endorsement.campaign.brand', 'approvals.reviewer']);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $commission,
            ]);
        }

        if (view()->exists('kol.commissions.show')) {
            return view('kol.commissions.show', compact('commission'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $commission,
        ]);
    }

    /**
     * Request disbursement for a single commission.
     */
    public function requestDisbursement(Request $request, Commission $commission)
    {
        $this->authorize('requestDisbursement', $commission);

        abort_unless($commission->status === 'approved', 422, 'Komisi belum dapat diajukan untuk pencairan.');
        abort_if($commission->approvals()->where('action', 'request')->exists(), 422, 'Pencairan komisi sudah pernah diajukan.');

        DB::transaction(function () use ($request, $commission): void {
            $commission->approvals()->create([
                'action' => 'request',
                'performed_by' => $request->user()->id,
                'notes' => $request->input('notes'),
            ]);
        });

        if (class_exists(AuditLogService::class)) {
            app(AuditLogService::class)->record(
                action: 'commission_disbursement_requested',
                entityType: 'commissions',
                entityId: $commission->id,
                oldValues: ['status' => $commission->status],
                newValues: ['approval_action' => 'request'],
                user: $request->user()
            );
        }

        if (class_exists(NotificationService::class)) {
            app(NotificationService::class)->notifySuperadmins(
                'commission_disbursement_requested',
                'Pengajuan pencairan komisi',
                $request->user()->name.' mengajukan pencairan komisi.',
                route('superadmin.endorsements.show', $commission->endorsement_id)
            );
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Pengajuan pencairan berhasil dikirim ke Superadmin.',
            ]);
        }

        return back()->with('success', 'Pengajuan pencairan berhasil dikirim ke Superadmin.');
    }

    /**
     * Request disbursement for selected commissions (Batch).
     */
    public function requestDisbursementBatch(RequestDisbursementRequest $request)
    {
        $user = auth()->user() ?? User::whereHas('kolProfile')->first();

        if (! $user) {
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
