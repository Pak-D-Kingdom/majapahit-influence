<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCampaignRequest;
use App\Http\Requests\Admin\UpdateCampaignRequest;
use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\KolProfile;
use App\Services\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {}

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
     * Show the form for creating a new campaign.
     */
    public function create(): View
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('superadmin.campaigns.create', compact('brands'));
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(StoreCampaignRequest $request): RedirectResponse|JsonResponse
    {
        $campaign = $this->campaignService->store(
            data: $request->validated(),
            files: $request->file('brief_files', []),
            creator: Auth::user()
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Campaign berhasil dibuat.',
                'data' => $campaign->load('files'),
            ], 201);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)
            ->with('success', 'Campaign berhasil dibuat.');
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

        // Active KOLs available for assignment (Dev 2 integration: KolProfile::active())
        $availableKols = KolProfile::with(['user', 'tier', 'niches', 'rateCards'])
            ->active()
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
     * Show the form for editing the specified campaign.
     */
    public function edit(Campaign $campaign): View
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('superadmin.campaigns.edit', compact('campaign', 'brands'));
    }

    /**
     * Update the specified campaign.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign): RedirectResponse|JsonResponse
    {
        $updatedCampaign = $this->campaignService->update(
            campaign: $campaign,
            data: $request->validated(),
            files: $request->file('brief_files', [])
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Campaign berhasil diperbarui.',
                'data' => $updatedCampaign,
            ]);
        }

        return redirect()->route('superadmin.campaigns.show', $campaign)
            ->with('success', 'Campaign berhasil diperbarui.');
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
