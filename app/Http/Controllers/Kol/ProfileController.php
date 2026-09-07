<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\UpdateProfileRequest;
use App\Models\Niche;
use App\Models\Tier;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan profil sendiri
     */
    public function show(Request $request): View|JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        $profile = $user->kolProfile()
            ->with(['user', 'tier', 'niches', 'socialMedia', 'rateCards', 'endorsements', 'commissions'])
            ->first();

        if (! $profile) {
            abort(404, 'Profil KOL belum tersedia.');
        }

        if ($request->expectsJson()) {
            return response()->json(['kol' => $profile]);
        }

        $endorsements = $profile->endorsements();

        $stats = [
            'completedEndorsements' => (clone $endorsements)->where('status', 'selesai')->count(),
            'totalCommission' => $profile->commissions()->where('status', 'dicairkan')->sum('commission_amount'),
            'monthCommission' => $profile->commissions()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount'),
        ];

        return view('kol.profile.show', [
            'profile' => $profile,
            'stats' => $stats,
        ]);
    }

    /**
     * Form edit profil
     */
    public function edit(Request $request): View|JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            abort(401);
        }

        $profile = $user->kolProfile()
            ->with(['user', 'tier', 'niches', 'socialMedia', 'rateCards'])
            ->first();

        if (! $profile) {
            abort(404, 'Profil KOL belum tersedia.');
        }

        if ($request->expectsJson()) {
            $tiers = Tier::all();
            $niches = Niche::all();

            return response()->json(['kol' => $profile, 'tiers' => $tiers, 'niches' => $niches]);
        }

        return view('kol.profile.edit', [
            'profile' => $profile,
        ]);
    }

    /**
     * Update profil
     */
    public function update(UpdateProfileRequest $request): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $profile = $user?->kolProfile()->first();

        if (! $profile) {
            abort(404, 'Profil KOL tidak ditemukan.');
        }

        DB::transaction(function () use ($request, $profile, $user): void {
            if ($request->hasFile('photo')) {
                if ($profile->photo_path) {
                    Storage::disk('public')->delete($profile->photo_path);
                }
                $profile->photo_path = $request->file('photo')->store("kol-photos/{$profile->id}", 'public');
            }

            $profile->fill($request->only([
                'nickname',
                'bio',
                'city',
                'province',
                'bank_name',
                'bank_account_number',
                'bank_account_name',
                'npwp',
            ]));
            $profile->save();

            // Sync Social Media
            if ($request->has('social_media')) {
                $submittedSocials = $request->input('social_media', []);
                $keptSocialIds = [];

                foreach ($submittedSocials as $item) {
                    if (! empty($item['id']) && ($existing = $profile->socialMedia()->find($item['id']))) {
                        $existing->update([
                            'platform' => $item['platform'],
                            'username' => $item['username'],
                            'profile_url' => $item['profile_url'] ?? null,
                            'followers_count' => $item['followers_count'],
                            'engagement_rate' => $item['engagement_rate'],
                        ]);
                        $keptSocialIds[] = $existing->id;
                    } else {
                        $newSocial = $profile->socialMedia()->create([
                            'platform' => $item['platform'],
                            'username' => $item['username'],
                            'profile_url' => $item['profile_url'] ?? null,
                            'followers_count' => $item['followers_count'],
                            'engagement_rate' => $item['engagement_rate'],
                        ]);
                        $keptSocialIds[] = $newSocial->id;
                    }
                }

                $profile->socialMedia()->whereNotIn('id', $keptSocialIds)->delete();
            }

            // Sync Rate Cards & Record Audit Log
            if ($request->has('rate_cards')) {
                $oldRateCards = $profile->rateCards()->get(['id', 'platform', 'content_type', 'rate'])->toArray();
                $submittedRates = $request->input('rate_cards', []);
                $keptRateIds = [];

                foreach ($submittedRates as $item) {
                    if (! empty($item['id']) && ($existing = $profile->rateCards()->find($item['id']))) {
                        $existing->update([
                            'platform' => $item['platform'],
                            'content_type' => $item['content_type'],
                            'rate' => $item['rate'],
                        ]);
                        $keptRateIds[] = $existing->id;
                    } else {
                        $rateCard = $profile->rateCards()->updateOrCreate(
                            [
                                'platform' => $item['platform'],
                                'content_type' => $item['content_type'],
                            ],
                            [
                                'rate' => $item['rate'],
                            ]
                        );
                        $keptRateIds[] = $rateCard->id;
                    }
                }

                $profile->rateCards()->whereNotIn('id', $keptRateIds)->delete();

                $newRateCards = $profile->rateCards()->get(['id', 'platform', 'content_type', 'rate'])->toArray();

                if (class_exists(AuditLogService::class)) {
                    app(AuditLogService::class)->record(
                        action: 'rate_card_updated',
                        entityType: 'kol_profiles',
                        entityId: $profile->id,
                        oldValues: ['rate_cards' => $oldRateCards],
                        newValues: ['rate_cards' => $newRateCards],
                        user: $user,
                    );
                }
            }
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Profil berhasil diperbarui.']);
        }

        return redirect()->route('kol.profile.show')->with('success', 'Profil dan rate card berhasil diperbarui.');
    }
}
