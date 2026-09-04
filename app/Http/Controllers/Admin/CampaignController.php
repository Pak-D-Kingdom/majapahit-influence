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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function __construct(
        protected CampaignService $campaignService
    ) {}

    /**
     * Display a listing of campaigns with filters.
     */
    public function index(Request $request): JsonResponse
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

        return response()->json($campaigns);
    }

    /**
     * Store a newly created campaign in storage.
     */
    public function store(StoreCampaignRequest $request): JsonResponse
    {
        $campaign = $this->campaignService->store(
            data: $request->validated(),
            files: $request->file('brief_files', []),
            creator: Auth::user()
        );

        return response()->json([
            'message' => 'Campaign berhasil dibuat.',
            'data' => $campaign->load('files'),
        ], 201);
    }

    /**
     * Display the specified campaign with endorsements progress.
     */
    public function show(Request $request, Campaign $campaign): JsonResponse
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

    /**
     * Update the specified campaign.
     */
    public function update(UpdateCampaignRequest $request, Campaign $campaign): JsonResponse
    {
        $updatedCampaign = $this->campaignService->update(
            campaign: $campaign,
            data: $request->validated(),
            files: $request->file('brief_files', [])
        );

        return response()->json([
            'message' => 'Campaign berhasil diperbarui.',
            'data' => $updatedCampaign,
        ]);
    }

    /**
     * Soft delete the specified campaign.
     */
    public function destroy(Request $request, Campaign $campaign): JsonResponse
    {
        $campaignId = $campaign->id;
        $campaign->delete();

        AuditLog::log(
            action: 'delete_campaign',
            entityType: 'campaign',
            entityId: $campaignId
        );

        return response()->json([
            'message' => 'Campaign berhasil dihapus.',
        ]);
    }
}
