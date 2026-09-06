<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kol\UpdateProfileRequest;
use App\Services\AuditLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(): View
    {
        $user = request()->user();
        $profile = $user->kolProfile()
            ->with(['user', 'tier', 'niches', 'socialMedia', 'rateCards'])
            ->first();

        if (! $profile) {
            abort(404, 'Profil KOL belum tersedia.');
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

    public function edit(): View
    {
        $user = request()->user();
        $profile = $user->kolProfile()
            ->with(['user', 'tier', 'niches', 'socialMedia', 'rateCards'])
            ->first();

        if (! $profile) {
            abort(404, 'Profil KOL belum tersedia.');
        }

        return view('kol.profile.edit', [
            'profile' => $profile,
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->kolProfile()->firstOrFail();

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

            // Sync Rate Cards & Record Audit Log
            $oldRateCards = $profile->rateCards()->get(['id', 'platform', 'content_type', 'rate'])->toArray();
            $submittedRates = $request->input('rate_cards', []);
            $keptRateIds = [];

            foreach ($submittedRates as $item) {
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

            $profile->rateCards()->whereNotIn('id', $keptRateIds)->delete();

            $newRateCards = $profile->rateCards()->get(['id', 'platform', 'content_type', 'rate'])->toArray();

            app(AuditLogService::class)->record(
                action: 'rate_card_updated',
                entityType: 'kol_profiles',
                entityId: $profile->id,
                oldValues: ['rate_cards' => $oldRateCards],
                newValues: ['rate_cards' => $newRateCards],
                user: $user,
            );
        });

        return redirect()->route('kol.profile.show')->with('success', 'Profil dan rate card berhasil diperbarui.');
    }
}
