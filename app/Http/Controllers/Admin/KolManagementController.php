<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreKolManualRequest;
use App\Http\Requests\Admin\UpdateKolStatusRequest;
use App\Http\Requests\Kol\UpdateProfileRequest;
use App\Models\KolProfile;
use App\Models\Niche;
use App\Models\Tier;
use App\Models\User;
use App\Services\KolProfileService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KolManagementController extends Controller
{
    protected KolProfileService $service;

    public function __construct(KolProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * Tampilkan daftar KOL (Compound Filter)
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'search', 'niches', 'platforms', 'followers_min', 'followers_max', 'tier_id', 'status', 'city',
        ]);

        $query = $this->service->filter($filters);
        $kols = $query->paginate($request->input('per_page', 25))->withQueryString();

        $tiers = Tier::all();
        $niches = Niche::all();

        if ($request->wantsJson()) {
            return response()->json(compact('kols', 'filters', 'tiers', 'niches'));
        }

        return view('superadmin.kol.index', compact('kols', 'filters', 'tiers', 'niches'));
    }

    /**
     * Form tambah KOL manual
     */
    public function create(Request $request)
    {
        $tiers = Tier::all();
        $niches = Niche::all();

        if ($request->wantsJson()) {
            return response()->json(compact('tiers', 'niches'));
        }

        return view('superadmin.kol.form', [
            'mode' => 'create',
            'kol' => new KolProfile,
            'tiers' => $tiers,
            'niches' => $niches,
        ]);
    }

    /**
     * Simpan KOL manual
     */
    public function store(StoreKolManualRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['full_name'],
                'email' => $data['email'],
                'password' => Hash::make(Str::random(12)),
            ]);
            $user->assignRole('kol');

            $photoPath = null;
            if (isset($data['photo']) && $data['photo'] instanceof UploadedFile) {
                // we'll update it after getting profile ID
            }

            $profile = KolProfile::create([
                'user_id' => $user->id,
                'nickname' => $data['nickname'],
                'bio' => $data['bio'] ?? null,
                'city' => $data['city'] ?? null,
                'province' => $data['province'] ?? null,
                'tier_id' => $data['tier_id'] ?? null,
                'bank_name' => $data['bank_name'] ?? null,
                'bank_account_number' => $data['bank_account_number'] ?? null,
                'bank_account_name' => $data['bank_account_name'] ?? null,
                'status' => 'aktif',
                'joined_at' => now(),
            ]);

            if (isset($data['photo'])) {
                $photoPath = $data['photo']->store("profiles/{$profile->id}", 'public');
                $profile->update(['photo_path' => $photoPath]);
            }

            // Sync Niches
            if (! empty($data['niches'])) {
                $profile->niches()->sync($data['niches']);
            }

            // Sync Social Media
            if (! empty($data['social_media'])) {
                $profile->socialMedia()->createMany($data['social_media']);
            }

            // Sync Rate Cards
            if (! empty($data['rate_cards'])) {
                $profile->rateCards()->createMany($data['rate_cards']);
            }
        });

        if ($request->wantsJson()) {
            return response()->json(['message' => 'KOL berhasil ditambahkan secara manual.'], 201);
        }

        return redirect()->route('superadmin.kol.index')->with('success', 'KOL berhasil ditambahkan secara manual.');
    }

    /**
     * Detail KOL
     */
    public function show(Request $request, KolProfile $kol)
    {
        $kol->load(['user', 'tier', 'niches', 'socialMedia', 'rateCards', 'endorsements', 'commissions']);

        if ($request->wantsJson()) {
            return response()->json(compact('kol'));
        }

        return view('superadmin.kol.show', compact('kol'));
    }

    /**
     * Form edit KOL
     */
    public function edit(Request $request, KolProfile $kol)
    {
        $kol->load(['user', 'tier', 'niches', 'socialMedia', 'rateCards']);
        $tiers = Tier::all();
        $niches = Niche::all();

        if ($request->wantsJson()) {
            return response()->json(compact('kol', 'tiers', 'niches'));
        }

        return view('superadmin.kol.form', [
            'mode' => 'edit',
            'kol' => $kol,
            'tiers' => $tiers,
            'niches' => $niches,
        ]);
    }

    /**
     * Update profil KOL
     */
    public function update(UpdateProfileRequest $request, KolProfile $kol)
    {
        try {
            $superadmin = auth()->user() ?? User::firstOrCreate(['email' => 'superadmin@majapahit.com'], ['name' => 'Superadmin', 'password' => bcrypt('password')]);
            $this->service->updateProfile($kol, $request->validated(), $superadmin);

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Profil KOL berhasil diperbarui.']);
            }

            return redirect()->route('superadmin.kol.show', $kol)->with('success', 'Profil KOL berhasil diperbarui.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal update: '.$e->getMessage()], 422);
            }

            return back()->withErrors(['error' => 'Gagal update: '.$e->getMessage()])->withInput();
        }
    }

    /**
     * Update status KOL
     */
    public function updateStatus(UpdateKolStatusRequest $request, KolProfile $kol)
    {
        try {
            $superadmin = auth()->user() ?? User::firstOrCreate(['email' => 'superadmin@majapahit.com'], ['name' => 'Superadmin', 'password' => bcrypt('password')]);
            $this->service->changeStatus(
                $kol,
                $request->input('status'),
                $request->input('status_reason'),
                $superadmin
            );

            if ($request->wantsJson()) {
                return response()->json(['message' => 'Status berhasil diubah.']);
            }

            return back()->with('success', 'Status berhasil diubah.');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal ubah status: '.$e->getMessage()], 422);
            }

            return back()->withErrors(['error' => 'Gagal ubah status: '.$e->getMessage()]);
        }
    }

    /**
     * Export KOL
     */
    public function export(Request $request)
    {
        return response()->json(['info' => 'Fitur export sedang dikembangkan oleh Dev 4.']);
    }
}
