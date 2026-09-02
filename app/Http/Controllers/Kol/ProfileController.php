<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\UpdateProfileRequest;
use App\Models\Niche;
use App\Models\Tier;
use App\Services\KolProfileService;

class ProfileController extends Controller
{
    protected KolProfileService $service;

    public function __construct(KolProfileService $service)
    {
        $this->service = $service;
    }

    /**
     * Tampilkan profil sendiri
     */
    public function show()
    {
        $kol = auth()->user()->kolProfile;
        
        if (!$kol) {
            return redirect()->route('dashboard')->with('error', 'Profil KOL tidak ditemukan.');
        }

        $kol->load(['tier', 'niches', 'socialMedia', 'rateCards', 'endorsements', 'commissions']);
        return view('kol.profile.show', compact('kol'));
    }

    /**
     * Form edit profil
     */
    public function edit()
    {
        $kol = auth()->user()->kolProfile;

        if (!$kol) {
            return redirect()->route('dashboard')->with('error', 'Profil KOL tidak ditemukan.');
        }

        $kol->load(['tier', 'niches', 'socialMedia', 'rateCards']);
        $tiers = Tier::all();
        $niches = Niche::all();

        return view('kol.profile.edit', compact('kol', 'tiers', 'niches'));
    }

    /**
     * Update profil
     */
    public function update(UpdateProfileRequest $request)
    {
        $kol = auth()->user()->kolProfile;

        if (!$kol) {
            return redirect()->route('dashboard')->with('error', 'Profil KOL tidak ditemukan.');
        }

        try {
            $this->service->updateProfile($kol, $request->validated(), auth()->user());
            return redirect()->route('kol.profile.show')->with('success', 'Profil berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
