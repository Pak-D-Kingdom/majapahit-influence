<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\EndorsementRequest;
use App\Http\Requests\Superadmin\ContentProofReviewRequest;
use App\Models\AuditLog;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\ContentProof;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\NotificationService;

class EndorsementController extends Controller
{
    private const TRANSITIONS = ['assigned' => ['in_progress', 'content_rejected'], 'in_progress' => ['content_submitted', 'content_rejected'], 'content_submitted' => ['content_approved', 'content_rejected'], 'content_approved' => ['selesai'], 'content_rejected' => ['in_progress'], 'selesai' => []];

    public function index(): View
    {
        $endorsements = Endorsement::with(['campaign.brand:id,name', 'kolProfile.user:id,name'])->when(request('search'), fn ($q, $search) => $q->whereHas('campaign', fn ($campaign) => $campaign->where('name', 'like', "%{$search}%"))->orWhereHas('kolProfile.user', fn ($user) => $user->where('name', 'like', "%{$search}%")))->when(request('status'), fn ($q, $status) => $q->where('status', $status))->when(request('campaign_id'), fn ($q, $id) => $q->where('campaign_id', $id))->orderByRaw("CASE WHEN deadline < CURDATE() AND status != 'selesai' THEN 0 ELSE 1 END")->orderBy('deadline')->paginate(15)->withQueryString();
        return view('superadmin.endorsements.index', ['endorsements' => $endorsements, 'campaigns' => Campaign::orderBy('name')->get(['id', 'name'])]);
    }

    public function show(Endorsement $endorsement): View { $this->authorize('view', $endorsement); return view('superadmin.endorsements.show', ['endorsement' => $endorsement->load(['campaign.brand', 'kolProfile.user', 'contentProofs.files', 'commission'])]); }
    public function edit(Endorsement $endorsement): View { $this->authorize('update', $endorsement); return view('superadmin.endorsements.form', compact('endorsement')); }

    public function update(EndorsementRequest $request, Endorsement $endorsement): RedirectResponse
    {
        $this->authorize('update', $endorsement);
        $data = $request->validated();
        $oldStatus = $endorsement->status;
        if ($oldStatus !== $data['status'] && ! in_array($data['status'], self::TRANSITIONS[$oldStatus] ?? [], true)) abort(422, 'Perubahan status endorsement tidak valid.');
        DB::transaction(function () use ($endorsement, $data, $oldStatus, $request): void {
            $endorsement->update($data);
            if ($oldStatus !== $data['status']) AuditLog::log('endorsement_status_changed', 'endorsements', $endorsement->id, ['status' => $oldStatus], ['status' => $data['status']], $request->user());
            if ($oldStatus !== $data['status']) app(NotificationService::class)->send($endorsement->load('kolProfile.user')->kolProfile->user, 'endorsement_status_changed', 'Status endorsement diperbarui', 'Status endorsement berubah dari '.$oldStatus.' menjadi '.$data['status'].'.', route('kol.endorsements.show', $endorsement));
        });
        return redirect()->route('superadmin.endorsements.show', $endorsement)->with('success', 'Endorsement berhasil diperbarui.');
    }

    public function reviewProof(ContentProofReviewRequest $request, Endorsement $endorsement, ContentProof $proof): RedirectResponse
    {
        $this->authorize('update', $endorsement);
        abort_unless($proof->endorsement_id === $endorsement->id, 404);
        $data = $request->validated();
        DB::transaction(function () use ($request, $endorsement, $proof, $data): void {
            $newStatus = $data['action'] === 'approve' ? 'approved' : 'rejected';
            $proof->update(['review_status' => $newStatus, 'review_notes' => $data['review_notes'] ?? null, 'reviewed_by' => $request->user()->id, 'reviewed_at' => now()]);
            $endorsement->update(['status' => $newStatus === 'approved' ? 'content_approved' : 'content_rejected']);
            app(NotificationService::class)->send($endorsement->load('kolProfile.user')->kolProfile->user, 'content_proof_reviewed', $newStatus === 'approved' ? 'Bukti konten disetujui' : 'Bukti konten ditolak', $newStatus === 'approved' ? 'Bukti kontenmu telah disetujui.' : 'Bukti kontenmu ditolak. Silakan cek catatan review.', route('kol.endorsements.show', $endorsement));
            AuditLog::log('content_proof_'.$data['action'].'d', 'content_proofs', $proof->id, ['review_status' => 'pending'], ['review_status' => $newStatus], $request->user());
        });
        return back()->with('success', 'Content proof berhasil '.($data['action'] === 'approve' ? 'disetujui.' : 'ditolak.'));
    }
}
