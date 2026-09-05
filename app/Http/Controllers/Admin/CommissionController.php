<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ApproveDisbursementRequest;
use App\Http\Requests\Admin\ProcessDisbursementRequest;
use App\Models\Commission;
use App\Models\KolProfile;
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

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
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

        // Calculate summary statistics
        $stats = [
            'total_pending' => Commission::where('status', 'pending')->sum('commission_amount'),
            'total_pending_review' => Commission::where('status', 'pending_review')->sum('commission_amount'),
            'total_approved' => Commission::where('status', 'approved')->sum('commission_amount'),
            'total_disbursed_this_month' => Commission::where('status', 'dicairkan')
                ->whereMonth('disbursed_at', now()->month)
                ->whereYear('disbursed_at', now()->year)
                ->sum('commission_amount'),
            'total_all_time' => Commission::sum('commission_amount'),
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
        $user = auth()->user() ?? \App\Models\User::first(); // Fallback for test environment if auth not yet set up
        
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
        $user = auth()->user() ?? \App\Models\User::first();

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
