<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCampaignRequest;
use App\Http\Requests\Admin\UpdateCampaignRequest;
use App\Models\AuditLog;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\KolProfile;
use App\Models\User;
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

        $campaigns = $query->latest()->paginate($request->input('per_page', 15))->withQueryString();

        if ($request->wantsJson()) {
            return response()->json($campaigns);
        }

        return view('superadmin.campaigns.index', compact('campaigns'));
    }

    /**
     * Show form for creating a new campaign.
     */
    public function create(Request $request): View|JsonResponse
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json(compact('brands'));
        }

        return view('superadmin.campaigns.form', [
            'mode' => 'create',
            'campaign' => new Campaign,
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(StoreCampaignRequest $request): RedirectResponse|JsonResponse
    {
        $admin = Auth::user() ?? User::first();

        $campaign = $this->campaignService->store(
            data: $request->validated(),
            files: $request->file('brief_files', []),
            creator: $admin
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Campaign berhasil dibuat.',
                'data' => $campaign->load('files'),
            ], 201);
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
                'available_kols' => $availableKols,
            ]);
        }

        return view('superadmin.campaigns.show', [
            'campaign' => $campaign,
            'kols' => $availableKols,
            'metrics' => [
                'total_endorsements' => $totalEndorsements,
                'completed_endorsements' => $completedEndorsements,
                'progress_pct' => $progressPct,
            ],
        ]);
    }

    /**
     * Show form for editing a campaign.
     */
    public function edit(Request $request, Campaign $campaign): View|JsonResponse
    {
        $brands = Brand::where('is_active', true)->orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json(compact('campaign', 'brands'));
        }

        return view('superadmin.campaigns.form', [
            'mode' => 'edit',
            'campaign' => $campaign,
            'brands' => $brands,
        ]);
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
            return response()->json([
                'message' => 'Campaign berhasil dihapus.',
            ]);
        }

        return redirect()->route('superadmin.campaigns.index')->with('success', 'Campaign berhasil dihapus.');
    }
}
