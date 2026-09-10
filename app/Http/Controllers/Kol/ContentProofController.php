<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\ContentProofRequest;
use App\Models\Endorsement;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ContentProofController extends Controller
{
    public function create(Endorsement $endorsement): View
    {
        $this->authorize('view', $endorsement);
        abort_unless(in_array($endorsement->status, ['assigned', 'in_progress', 'content_rejected']), 403, 'Tidak dapat mengunggah bukti pada status ini.');

        return view('kol.endorsements.proof', compact('endorsement'));
    }

    public function store(ContentProofRequest $request, Endorsement $endorsement): RedirectResponse
    {
        $this->authorize('view', $endorsement);
        abort_unless(in_array($endorsement->status, ['assigned', 'in_progress', 'content_rejected']), 403, 'Tidak dapat mengunggah bukti pada status ini.');
        DB::transaction(function () use ($request, $endorsement): void {
            $data = $request->validated();
            $proof = $endorsement->contentProofs()->create(collect($data)->except('files')->all());
            foreach ($request->file('files') as $file) {
                $proof->files()->create(['file_path' => $file->store('content-proofs'), 'file_name' => $file->getClientOriginalName(), 'file_size' => $file->getSize(), 'mime_type' => $file->getMimeType()]);
            }
            $endorsement->update(['status' => 'content_submitted']);
        });
        app(AuditLogService::class)->record('content_proof_submitted', 'endorsements', $endorsement->id, ['status' => 'in_progress'], ['status' => 'content_submitted'], $request->user());

        return redirect()->route('kol.endorsements.show', $endorsement)->with('success', 'Bukti konten berhasil dikirim untuk review.');
    }
}
