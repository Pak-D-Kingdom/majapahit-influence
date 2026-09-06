<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\Tier;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        protected ExportService $exportService
    ) {}

    /**
     * Display reporting dashboard.
     */
    public function index(Request $request)
    {
        $stats = [
            'total_kols' => KolProfile::count(),
            'total_commissions' => Commission::sum('commission_amount'),
            'total_disbursed' => Commission::where('status', 'dicairkan')->sum('commission_amount'),
            'total_endorsements' => Endorsement::count(),
        ];

        $tiers = Tier::all();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'stats' => $stats,
                'tiers' => $tiers,
            ]);
        }

        return view('superadmin.reports.index', compact('stats', 'tiers'));
    }

    /**
     * Export commissions report.
     */
    public function exportCommissions(Request $request)
    {
        return $this->exportService->exportCommissions($request->all());
    }

    /**
     * Export KOL profiles report.
     */
    public function exportKol(Request $request)
    {
        return $this->exportService->exportKolProfiles($request->all());
    }
}
