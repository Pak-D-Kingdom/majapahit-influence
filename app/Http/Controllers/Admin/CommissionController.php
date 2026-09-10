<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommissionStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveDisbursementRequest;
use App\Http\Requests\Admin\ProcessDisbursementRequest;
use App\Models\Commission;
use App\Models\KolProfile;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\ExportService;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function __construct(
        protected CommissionService $commissionService,
        protected ExportService $exportService
    ) {}

    /**
     * Display a listing of commissions with compound filters and summary statistics.
     */
    public function index(Request $request)
    {
        $query = Commission::with([
            'kolProfile.user',
            'endorsement.campaign.brand',
            'approvals.performedBy',
        ]);

        // Filter by status (validated against CommissionStatus enum)
        if ($request->filled('status') && $request->status !== 'all') {
            if ($statusEnum = CommissionStatus::tryFrom($request->status)) {
                $query->where('status', $statusEnum->value);
            }
        }

        // Filter by KOL
        if ($request->filled('kol_profile_id')) {
            $query->where('kol_profile_id', $request->kol_profile_id);
        }

        // Search by KOL name, nickname, or campaign title
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('kolProfile.user', function ($u) use ($search) {
                    $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })->orWhereHas('kolProfile', function ($k) use ($search) {
                    $k->where('nickname', 'like', "%{$search}%");
                })->orWhereHas('endorsement.campaign', function ($c) use ($search) {
                    $c->where('title', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Calculate summary statistics in a single aggregate query
        $startOfMonth = now()->startOfMonth()->toDateString();
        $endOfMonth = now()->endOfMonth()->toDateString();

        $statsRow = Commission::query()
            ->selectRaw("
                COALESCE(SUM(CASE WHEN status = 'pending' THEN commission_amount ELSE 0 END), 0) as total_pending,
                COALESCE(SUM(CASE WHEN status = 'pending_review' THEN commission_amount ELSE 0 END), 0) as total_pending_review,
                COALESCE(SUM(CASE WHEN status = 'approved' THEN commission_amount ELSE 0 END), 0) as total_approved,
                COALESCE(SUM(CASE WHEN status = 'dicairkan' AND DATE(disbursed_at) BETWEEN ? AND ? THEN commission_amount ELSE 0 END), 0) as total_disbursed_this_month,
                COALESCE(SUM(commission_amount), 0) as total_all_time
            ", [$startOfMonth, $endOfMonth])
            ->first();

        $stats = [
            'total_pending' => (float) ($statsRow->total_pending ?? 0),
            'total_pending_review' => (float) ($statsRow->total_pending_review ?? 0),
            'total_approved' => (float) ($statsRow->total_approved ?? 0),
            'total_disbursed_this_month' => (float) ($statsRow->total_disbursed_this_month ?? 0),
            'total_all_time' => (float) ($statsRow->total_all_time ?? 0),
        ];

        $perPage = (int) $request->get('per_page', 15);
        $commissions = $query->latest('id')->paginate($perPage)->withQueryString();

        // Dropdown data for filter
        $kolProfiles = KolProfile::with('user')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'stats' => $stats,
                'data' => $commissions,
            ]);
        }

        return view('superadmin.commissions.index', compact('commissions', 'stats', 'kolProfiles'));
    }

    /**
     * Display commission detail.
     */
    public function show(Request $request, Commission $commission)
    {
        $this->authorize('view', $commission);

        $commission->load([
            'kolProfile.user',
            'kolProfile.tier',
            'endorsement.campaign.brand',
            'approvals.performedBy',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => $commission,
            ]);
        }

        return view('superadmin.commissions.show', compact('commission'));
    }

    /**
     * Batch or single approve/reject disbursement requests.
     */
    public function approve(ApproveDisbursementRequest $request)
    {
        $user = $request->user() ?? auth()->user() ?? User::first();
        abort_unless($user && $user->isSuperadmin(), 403, 'Unauthorized.');

        $count = $this->commissionService->approveDisbursement(
            $request->validated('commission_ids'),
            $request->validated('status'),
            $request->validated('notes'),
            $user
        );

        $statusLabel = $request->validated('status') === 'approved' ? 'disetujui' : 'ditolak';

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => "Berhasil memproses {$count} pengajuan komisi ({$statusLabel}).",
                'processed_count' => $count,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', "Berhasil memproses {$count} pengajuan komisi ({$statusLabel}).");
    }

    /**
     * Mark commission as disbursed (upload transfer proof and set date).
     */
    public function process(Commission $commission, ProcessDisbursementRequest $request)
    {
        $user = $request->user() ?? auth()->user() ?? User::first();
        abort_unless($user, 401, 'Unauthenticated.');

        $updatedCommission = $this->commissionService->markAsDisbursed(
            $commission,
            $request->validated(),
            $request->file('transfer_proof'),
            $user
        );

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Komisi berhasil ditandai sebagai dicairkan dan bukti transfer telah tersimpan.',
                'data' => $updatedCommission,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Komisi berhasil ditandai sebagai dicairkan dan bukti transfer telah tersimpan.');
    }

    /**
     * Export commissions report to CSV.
     */
    public function export(Request $request)
    {
        return $this->exportService->exportCommissions($request->all());
    }
}
