<?php

namespace App\Http\Controllers\Kol;

use App\Enums\CommissionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\RequestDisbursementRequest;
use App\Models\Commission;
use App\Models\KolProfile;
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
            if ($statusEnum = CommissionStatus::tryFrom($request->status)) {
                $query->where('status', $statusEnum->value);
            }
        }

        // Summary statistics for this KOL in a single aggregate query
        $monthStart = now()->startOfMonth()->toDateTimeString();
        $monthEnd = now()->endOfMonth()->toDateTimeString();

        $statsRow = Commission::where('kol_profile_id', $kolProfile->id)
            ->selectRaw("
                COALESCE(SUM(commission_amount), 0) as total_all_time,
                COALESCE(SUM(CASE WHEN status IN ('pending', 'pending_review') THEN commission_amount ELSE 0 END), 0) as total_pending,
                COALESCE(SUM(CASE WHEN status = 'approved' THEN commission_amount ELSE 0 END), 0) as total_approved,
                COALESCE(SUM(CASE WHEN status = 'dicairkan' THEN commission_amount ELSE 0 END), 0) as total_disbursed,
                COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN commission_amount ELSE 0 END), 0) as month,
                COALESCE(SUM(CASE WHEN status IN ('pending', 'approved', 'pending_review') THEN commission_amount ELSE 0 END), 0) as pending
            ", [$monthStart, $monthEnd])
            ->first();

        $stats = [
            'total_all_time' => (float) ($statsRow->total_all_time ?? 0),
            'total_pending' => (float) ($statsRow->total_pending ?? 0),
            'total_approved' => (float) ($statsRow->total_approved ?? 0),
            'total_disbursed' => (float) ($statsRow->total_disbursed ?? 0),
            'month' => (float) ($statsRow->month ?? 0),
            'pending' => (float) ($statsRow->pending ?? 0),
            'disbursed' => (float) ($statsRow->total_disbursed ?? 0),
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

        $commission->load(['endorsement.campaign.brand', 'approvals.performer']);

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

        abort_unless(in_array($commission->status, ['pending', 'approved', 'rejected'], true), 422, 'Komisi belum dapat diajukan untuk pencairan.');
        abort_if($commission->approvals()->where('action', 'request')->exists(), 422, 'Pencairan komisi sudah pernah diajukan.');

        DB::transaction(function () use ($request, $commission): void {
            $commission->status = 'pending_review';
            $commission->save();

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
        $user = $request->user();
        abort_unless($user, 401, 'Unauthenticated.');

        if (! $user->kolProfile) {
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
