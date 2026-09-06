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
            return response()->json(['error' => 'Profil KOL tidak ditemukan.'], 404);
        }

        $kol->load(['tier', 'niches', 'socialMedia', 'rateCards', 'endorsements', 'commissions']);
        return response()->json(compact('kol'));
    }

    /**
     * Form edit profil
     */
    public function edit()
    {
        $kol = auth()->user()->kolProfile;

        if (!$kol) {
            return response()->json(['error' => 'Profil KOL tidak ditemukan.'], 404);
        }

        $kol->load(['tier', 'niches', 'socialMedia', 'rateCards']);
        $tiers = Tier::all();
        $niches = Niche::all();

        return response()->json(compact('kol', 'tiers', 'niches'));
    }

    /**
     * Update profil
     */
    public function update(UpdateProfileRequest $request)
    {
        $kol = auth()->user()->kolProfile;

        if (!$kol) {
            return response()->json(['error' => 'Profil KOL tidak ditemukan.'], 404);
        }

        try {
            $this->service->updateProfile($kol, $request->validated(), auth()->user());
            return response()->json(['message' => 'Profil berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal update: ' . $e->getMessage()], 422);
        }
    }
}
