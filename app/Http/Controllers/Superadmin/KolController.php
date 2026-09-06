<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Superadmin\KolProfileRequest;
use App\Models\KolProfile;
use App\Models\KolRateCard;
use App\Models\KolSocialMedia;
use App\Models\Niche;
use App\Models\Role;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Services\AuditLogService;

class KolController extends Controller
{
    public function index(): View
    {
        $sorts = ['name' => 'name', 'followers' => 'followers_count', 'engagement' => 'engagement_rate', 'joined' => 'joined_at'];
        $sort = request('sort', 'joined');
        $direction = request('direction') === 'asc' ? 'asc' : 'desc';

        $kols = KolProfile::query()
            ->select('kol_profiles.*')
            ->with(['user:id,name,email', 'tier:id,name', 'niches:id,name', 'socialMedia:id,kol_profile_id,platform,username,followers_count,engagement_rate'])
            ->when(request('search'), fn ($query, string $search) => $query->whereHas('user', fn ($userQuery) => $userQuery->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))))
            ->when(request('status'), fn ($query, string $status) => $query->where('status', $status))
            ->when(request('tier_id'), fn ($query, $tierId) => $query->where('tier_id', $tierId))
            ->when(request('platform'), fn ($query, string $platform) => $query->whereHas('socialMedia', fn ($social) => $social->where('platform', $platform)))
            ->when(request('niche_id'), fn ($query, $nicheId) => $query->whereHas('niches', fn ($niche) => $niche->whereKey($nicheId)))
            ->when($sort === 'name', fn ($query) => $query->join('users', 'users.id', '=', 'kol_profiles.user_id')->orderBy('users.name', $direction), fn ($query) => $query->orderBy($sorts[$sort] ?? 'joined_at', $direction))
            ->paginate((int) min(100, max(10, request('per_page', 10))))
            ->withQueryString();

        return view('superadmin.kol.index', ['kols' => $kols, 'tiers' => Tier::orderBy('min_followers')->get(['id', 'name']), 'niches' => Niche::where('is_active', true)->orderBy('name')->get(['id', 'name'])]);
    }

    public function create(): View
    {
        return view('superadmin.kol.form', ['kol' => new KolProfile(), 'tiers' => Tier::orderBy('min_followers')->get(), 'niches' => Niche::where('is_active', true)->orderBy('name')->get(), 'mode' => 'create']);
    }

    public function store(KolProfileRequest $request): RedirectResponse
    {
        $kol = DB::transaction(function () use ($request): KolProfile {
            $data = $request->validated();
            $user = User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make($data['password']), 'is_active' => $data['status'] === 'aktif']);
            $user->assignRole(Role::where('name', 'kol')->firstOrFail());
            $profile = $user->kolProfile()->create(collect($data)->only(['nickname', 'bio', 'city', 'province', 'tier_id', 'status'])->put('joined_at', now())->all());
            $this->syncProfileDetails($profile, $data);
            return $profile;
        });

        app(AuditLogService::class)->record('kol_created', 'kol_profiles', $kol->id, null, ['user_id' => $kol->user_id, 'status' => $kol->status], $request->user());
        return redirect()->route('superadmin.kol.show', $kol)->with('success', 'Data KOL berhasil ditambahkan.');
    }

    public function show(KolProfile $kol): View
    {
        $this->authorize('view', $kol);
        $kol->load(['user', 'tier', 'niches', 'socialMedia', 'rateCards', 'endorsements.campaign.brand', 'commissions']);
        return view('superadmin.kol.show', compact('kol'));
    }

    public function edit(KolProfile $kol): View
    {
        $this->authorize('update', $kol);
        $kol->load(['user', 'niches', 'socialMedia']);
        return view('superadmin.kol.form', ['kol' => $kol, 'tiers' => Tier::orderBy('min_followers')->get(), 'niches' => Niche::where('is_active', true)->orderBy('name')->get(), 'mode' => 'edit']);
    }

    public function update(KolProfileRequest $request, KolProfile $kol): RedirectResponse
    {
        $this->authorize('update', $kol);
        $oldValues = $kol->only(['nickname', 'bio', 'city', 'province', 'tier_id', 'status']);
        DB::transaction(function () use ($request, $kol): void {
            $data = $request->validated();
            $kol->user->update(['name' => $data['name'], 'email' => $data['email'], 'is_active' => $data['status'] === 'aktif']);
            if (! empty($data['password'])) $kol->user->update(['password' => Hash::make($data['password'])]);
            $kol->update(collect($data)->only(['nickname', 'bio', 'city', 'province', 'tier_id', 'status'])->all());
            $this->syncProfileDetails($kol, $data);
        });

        app(AuditLogService::class)->record('kol_updated', 'kol_profiles', $kol->id, $oldValues, $kol->fresh()->only(['nickname', 'bio', 'city', 'province', 'tier_id', 'status']), $request->user());
        return redirect()->route('superadmin.kol.show', $kol)->with('success', 'Data KOL berhasil diperbarui.');
    }

    private function syncProfileDetails(KolProfile $profile, array $data): void
    {
        $profile->niches()->sync($data['niches'] ?? []);
        KolSocialMedia::updateOrCreate(['kol_profile_id' => $profile->id, 'platform' => $data['platform']], collect($data)->only(['username', 'profile_url', 'followers_count', 'engagement_rate'])->all());
        KolRateCard::firstOrCreate(['kol_profile_id' => $profile->id, 'platform' => $data['platform'], 'content_type' => 'video'], ['rate' => 0]);
    }
}
