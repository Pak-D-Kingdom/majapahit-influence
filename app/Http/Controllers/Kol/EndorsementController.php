<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Models\Endorsement;
use Illuminate\View\View;

class EndorsementController extends Controller
{
    public function index(): View
    {
        $profile = request()->user()->kolProfile()->firstOrFail();
        $tab = request('tab', 'aktif');
        $query = $profile->endorsements()->with(['campaign.brand:id,name']);

        match ($tab) {
            'mendatang' => $query->whereDate('start_date', '>', now()->toDateString())->whereNot('status', 'selesai'),
            'riwayat' => $query->where('status', 'selesai'),
            default => $query->whereIn('status', ['assigned', 'in_progress', 'content_submitted', 'content_approved', 'content_rejected']),
        };

        return view('kol.endorsements.index', ['endorsements' => $query->orderBy('deadline')->paginate(10)->withQueryString(), 'tab' => $tab]);
    }

    public function show(Endorsement $endorsement): View
    {
        $this->authorize('view', $endorsement);
        return view('kol.endorsements.show', ['endorsement' => $endorsement->load(['campaign.brand', 'contentProofs.files', 'commission'])]);
    }
}
