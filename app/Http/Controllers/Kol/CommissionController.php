<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\DisbursementRequest;
use App\Models\Commission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\NotificationService;
use App\Services\AuditLogService;

class CommissionController extends Controller
{
    public function index(): View
    {
        $profile = request()->user()->kolProfile()->firstOrFail();
        $commissions = $profile->commissions()->with(['endorsement.campaign.brand:id,name'])->latest()->paginate(10)->withQueryString();
        return view('kol.commissions.index', ['commissions' => $commissions, 'stats' => ['month' => $profile->commissions()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount'), 'pending' => $profile->commissions()->whereIn('status', ['pending', 'approved'])->sum('commission_amount'), 'disbursed' => $profile->commissions()->where('status', 'dicairkan')->sum('commission_amount')]]);
    }

    public function show(Commission $commission): View
    {
        $this->authorize('view', $commission);
        return view('kol.commissions.show', ['commission' => $commission->load(['endorsement.campaign.brand', 'approvals.performer'])]);
    }

    public function requestDisbursement(DisbursementRequest $request, Commission $commission): RedirectResponse
    {
        $this->authorize('requestDisbursement', $commission);
        abort_unless($commission->status === 'approved', 422, 'Komisi belum dapat diajukan untuk pencairan.');
        abort_if($commission->approvals()->where('action', 'request')->exists(), 422, 'Pencairan komisi sudah pernah diajukan.');
        DB::transaction(fn () => $commission->approvals()->create(['action' => 'request', 'performed_by' => $request->user()->id, 'notes' => $request->validated('notes')]));
        app(AuditLogService::class)->record('commission_disbursement_requested', 'commissions', $commission->id, ['status' => $commission->status], ['approval_action' => 'request'], $request->user());
        app(NotificationService::class)->notifySuperadmins('commission_disbursement_requested', 'Pengajuan pencairan komisi', $request->user()->name.' mengajukan pencairan komisi.', route('superadmin.endorsements.show', $commission->endorsement_id));
        return back()->with('success', 'Pengajuan pencairan berhasil dikirim ke Superadmin.');
    }

}
