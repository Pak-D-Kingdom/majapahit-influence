<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\AssignmentRequest;
use App\Http\Requests\Superadmin\CampaignRequest;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Endorsement;
use App\Models\KolProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Services\NotificationService;
use App\Services\AuditLogService;

class CampaignController extends Controller
{
    public function index(): View { return view('superadmin.campaigns.index', ['campaigns' => Campaign::with('brand')->withCount('endorsements')->when(request('status'), fn ($q, $status) => $q->where('status', $status))->latest()->paginate(15)->withQueryString()]); }
    public function create(): View { return view('superadmin.campaigns.form', ['campaign' => new Campaign(['status' => 'draft']), 'brands' => Brand::where('is_active', true)->orderBy('name')->get(), 'mode' => 'create']); }
    public function store(CampaignRequest $request): RedirectResponse { $campaign = DB::transaction(function () use ($request): Campaign { $data = $request->validated(); $campaign = Campaign::create(collect($data)->except('brief')->put('created_by', $request->user()->id)->all()); if ($request->hasFile('brief')) $campaign->files()->create(['file_path' => $request->file('brief')->store('campaigns'), 'file_name' => $request->file('brief')->getClientOriginalName(), 'file_size' => $request->file('brief')->getSize(), 'mime_type' => $request->file('brief')->getMimeType()]); return $campaign; }); app(AuditLogService::class)->record('campaign_created', 'campaigns', $campaign->id, null, $campaign->only(['name', 'brand_id', 'status', 'budget']), $request->user()); return redirect()->route('superadmin.campaigns.show', $campaign)->with('success', 'Campaign berhasil dibuat.'); }
    public function show(Campaign $campaign): View { return view('superadmin.campaigns.show', ['campaign' => $campaign->load(['brand', 'files', 'endorsements.kolProfile.user', 'endorsements.commission']), 'kols' => KolProfile::with('user')->active()->orderBy('id')->get()]); }
    public function edit(Campaign $campaign): View { return view('superadmin.campaigns.form', ['campaign' => $campaign, 'brands' => Brand::where('is_active', true)->orderBy('name')->get(), 'mode' => 'edit']); }
    public function update(CampaignRequest $request, Campaign $campaign): RedirectResponse { $oldValues = $campaign->only(['name', 'brand_id', 'status', 'budget']); $campaign->update(collect($request->validated())->except('brief')->all()); if ($request->hasFile('brief')) $campaign->files()->create(['file_path' => $request->file('brief')->store('campaigns'), 'file_name' => $request->file('brief')->getClientOriginalName(), 'file_size' => $request->file('brief')->getSize(), 'mime_type' => $request->file('brief')->getMimeType()]); app(AuditLogService::class)->record('campaign_updated', 'campaigns', $campaign->id, $oldValues, $campaign->fresh()->only(['name', 'brand_id', 'status', 'budget']), $request->user()); return redirect()->route('superadmin.campaigns.show', $campaign)->with('success', 'Campaign berhasil diperbarui.'); }
    public function assign(AssignmentRequest $request, Campaign $campaign): RedirectResponse { $data = $request->validated(); $endorsement = Endorsement::updateOrCreate(['campaign_id' => $campaign->id, 'kol_profile_id' => $data['kol_profile_id'], 'content_type' => $data['content_type']], collect($data)->except('kol_profile_id')->put('campaign_id', $campaign->id)->put('kol_profile_id', $data['kol_profile_id'])->put('assigned_by', $request->user()->id)->all()); if ($endorsement->wasRecentlyCreated) { app(NotificationService::class)->endorsementAssigned($endorsement); app(AuditLogService::class)->record('endorsement_assigned', 'endorsements', $endorsement->id, null, ['campaign_id' => $campaign->id, 'kol_profile_id' => $data['kol_profile_id']], $request->user()); } return back()->with('success', 'KOL berhasil di-assign ke campaign.'); }
}
