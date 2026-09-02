<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaign\StoreCampaignRequest;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\CampaignFile;
use App\Models\KolProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns with filters.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Campaign::query()->with(['brand', 'creator'])
            ->withCount(['endorsements']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('brand', function ($b) use ($search) {
                      $b->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('brand_id')) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $campaigns = $query->latest()->paginate($request->input('per_page', 15));

        if ($request->wantsJson()) {
            return response()->json($campaigns);
        }

        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        return view('superadmin.campaigns.index', compact('campaigns', 'brands'));
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(StoreCampaignRequest $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        $campaign = DB::transaction(function () use ($request, $validated) {
            $campaign = Campaign::create($validated);

            if ($request->hasFile('brief_files')) {
                foreach ($request->file('brief_files') as $file) {
                    $path = $file->store('campaign_briefs', 'public');
                    CampaignFile::create([
                        'campaign_id' => $campaign->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            AuditLog::log(
                action: 'create_campaign',
                entityType: 'campaign',
                entityId: $campaign->id,
                newValues: $campaign->toArray()
            );

            return $campaign;
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Campaign berhasil dibuat.', 'data' => $campaign->load('files')], 201);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)->with('success', 'Campaign berhasil dibuat.');
    }

    /**
     * Display the specified campaign with endorsements progress.
     */
    public function show(Request $request, Campaign $campaign): View|JsonResponse
    {
        $campaign->load([
            'brand',
            'creator',
            'files',
            'endorsements.kolProfile.user',
            'endorsements.kolProfile.tier',
            'endorsements.latestContentProof.files',
            'endorsements.commission',
        ]);

        $totalEndorsements = $campaign->endorsements->count();
        $completedEndorsements = $campaign->endorsements->where('status', 'selesai')->count();
        $progressPct = $totalEndorsements > 0 ? round(($completedEndorsements / $totalEndorsements) * 100, 1) : 0;

        // Active KOLs available for assignment
        $availableKols = KolProfile::with(['user', 'tier', 'niches', 'rateCards'])
            ->where('status', 'aktif')
            ->get();

        if ($request->wantsJson()) {
            return response()->json([
                'campaign' => $campaign,
                'metrics' => [
                    'total_endorsements' => $totalEndorsements,
                    'completed_endorsements' => $completedEndorsements,
                    'progress_pct' => $progressPct,
                ],
            ]);
        }

        return view('superadmin.campaigns.show', compact('campaign', 'totalEndorsements', 'completedEndorsements', 'progressPct', 'availableKols'));
    }

    /**
     * Update the specified campaign.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign): RedirectResponse|JsonResponse
    {
        $validated = $request->validated();
        $oldValues = $campaign->toArray();

        DB::transaction(function () use ($request, $campaign, $validated, $oldValues) {
            $campaign->update($validated);

            if ($request->hasFile('brief_files')) {
                foreach ($request->file('brief_files') as $file) {
                    $path = $file->store('campaign_briefs', 'public');
                    CampaignFile::create([
                        'campaign_id' => $campaign->id,
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'file_size' => $file->getSize(),
                        'mime_type' => $file->getClientMimeType(),
                    ]);
                }
            }

            AuditLog::log(
                action: 'update_campaign',
                entityType: 'campaign',
                entityId: $campaign->id,
                oldValues: $oldValues,
                newValues: $campaign->fresh()->toArray()
            );
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Campaign berhasil diperbarui.', 'data' => $campaign->fresh(['brand', 'files'])]);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)->with('success', 'Campaign berhasil diperbarui.');
    }

    /**
     * Soft delete the specified campaign.
     */
    public function destroy(Request $request, Campaign $campaign): RedirectResponse|JsonResponse
    {
        $campaignId = $campaign->id;
        $campaign->delete();

        AuditLog::log(
            action: 'delete_campaign',
            entityType: 'campaign',
            entityId: $campaignId
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Campaign berhasil dihapus.']);
        }

        return redirect()->route('superadmin.campaigns.index')->with('success', 'Campaign berhasil dihapus.');
    }
}
