<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Models\KolProfile;
use App\Models\Tier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaderboardController extends Controller
{
    /**
     * Display the dynamic KOL Leaderboard.
     */
    public function index(Request $request): View
    {
        $currentKol = $request->user()?->kolProfile;
        $period = $request->input('period', 'this_month');
        $tierId = $request->input('tier_id');

        $query = KolProfile::query()
            ->where('status', 'aktif')
            ->with([
                'user:id,name,email',
                'tier:id,name',
                'niches:id,name',
                'socialMedia',
            ])
            ->withCount([
                'endorsements as completed_endorsements_count' => function ($q) use ($period) {
                    $q->whereIn('status', ['selesai', 'completed', 'content_approved', 'approved']);
                    if ($period === 'this_month') {
                        $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    }
                },
                'endorsements as total_endorsements_count' => function ($q) use ($period) {
                    if ($period === 'this_month') {
                        $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    }
                },
            ])
            ->withSum([
                'commissions as total_commission' => function ($q) use ($period) {
                    if ($period === 'this_month') {
                        $q->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                    }
                },
            ], 'commission_amount');

        if (! empty($tierId)) {
            $query->where('tier_id', $tierId);
        }

        $allKols = $query->get();

        // Calculate gamification points score for each KOL
        $rankedKols = $allKols->map(function (KolProfile $kol) {
            $completed = (int) $kol->completed_endorsements_count;
            $totalEndorsements = (int) $kol->total_endorsements_count;
            $totalFollowers = (int) $kol->socialMedia->sum('followers_count');
            $commissionsSum = (float) ($kol->total_commission ?? 0);

            // Points formula:
            // 150 pts per completed campaign + 30 pts per active campaign + follower bonus + commission points
            $score = ($completed * 150)
                + ($totalEndorsements * 30)
                + min(200, (int) ($totalFollowers / 5000))
                + min(300, (int) ($commissionsSum / 100000));

            $kol->score = $score;
            $kol->total_followers = $totalFollowers;

            return $kol;
        })->sortByDesc('score')->values();

        // Assign Rank Position (1, 2, 3...)
        $rankedKols = $rankedKols->map(function (KolProfile $kol, int $index) {
            $kol->rank = $index + 1;

            return $kol;
        });

        // Current logged-in KOL's position
        $myRankRecord = null;
        if ($currentKol) {
            $myRankRecord = $rankedKols->firstWhere('id', $currentKol->id);
        }

        $topThree = $rankedKols->take(3);
        $remainingKols = $rankedKols->slice(3)->values();
        $tiers = Tier::orderBy('id')->get();

        return view('kol.leaderboard.index', [
            'topThree' => $topThree,
            'remainingKols' => $remainingKols,
            'allKols' => $rankedKols,
            'myRank' => $myRankRecord,
            'currentPeriod' => $period,
            'selectedTierId' => $tierId,
            'tiers' => $tiers,
            'totalParticipants' => $rankedKols->count(),
        ]);
    }
}
