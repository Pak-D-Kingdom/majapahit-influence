<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Commission;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\KolRegistration;
use App\Models\Notification;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $trendDatasets = [
            'weekly' => $this->buildWeeklyTrend(8),
            '1m' => $this->buildDailyTrend(30),
            '6m' => $this->buildMonthlyTrend(6, '6 Bulan'),
            '1y' => $this->buildMonthlyTrend(12, '1 Tahun'),
        ];

        $defaultDataset = $trendDatasets['6m'];
        $deadlineEnd = now()->addDays(7)->endOfDay();

        return view('superadmin.dashboard', [
            'stats' => [
                'activeKols' => KolProfile::active()->count(),
                'activeEndorsements' => Endorsement::whereIn('status', ['assigned', 'in_progress', 'content_submitted'])->count(),
                'pendingRegistrations' => KolRegistration::where('status', 'pending_review')->count(),
                'pendingDisbursements' => Commission::whereIn('status', ['pending', 'approved'])->count(),
                'unpaidCommission' => Commission::whereIn('status', ['pending', 'approved'])->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount'),
            ],
            'endorsementTrend' => $defaultDataset['items'],
            'trendSummary' => $defaultDataset['summary'],
            'trendDatasets' => $trendDatasets,
            'upcomingEndorsements' => Endorsement::with(['kolProfile.user:id,name', 'campaign:id,name,brand_id', 'campaign.brand:id,name'])
                ->whereBetween('deadline', [now()->toDateString(), $deadlineEnd->toDateString()])
                ->whereNot('status', 'selesai')
                ->orderBy('deadline')
                ->limit(5)
                ->get(),
            'recentRegistrations' => KolRegistration::latest()->limit(5)->get(),
            'notifications' => Notification::where('user_id', auth()->id())->latest()->limit(5)->get(),
            'totalCampaigns' => Campaign::where('status', 'aktif')->count(),
        ]);
    }

    /**
     * Build monthly trend dataset.
     */
    protected function buildMonthlyTrend(int $months, string $periodLabel): array
    {
        $period = CarbonPeriod::create(now()->subMonths($months - 1)->startOfMonth(), '1 month', now()->startOfMonth());
        $items = collect($period)->mapWithKeys(function (Carbon $month): array {
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            $endorsementsCount = Endorsement::whereBetween('created_at', [$start, $end])->count();
            $commissionAmount = (float) Commission::whereBetween('created_at', [$start, $end])->sum('commission_amount');
            $feeAmount = (float) Commission::whereBetween('created_at', [$start, $end])->sum('endorsement_fee');

            return [
                $month->format('Y-m') => [
                    'label' => $month->translatedFormat('M Y'),
                    'short_label' => $month->translatedFormat('M'),
                    'total' => $endorsementsCount,
                    'endorsements_count' => $endorsementsCount,
                    'commission_amount' => $commissionAmount,
                    'fee_amount' => $feeAmount,
                ],
            ];
        });

        $current = $items->last();
        $previous = $items->count() >= 2 ? $items->slice(-2, 1)->first() : null;

        $volumeGrowth = 0;
        if ($previous && $previous['endorsements_count'] > 0) {
            $volumeGrowth = round((($current['endorsements_count'] - $previous['endorsements_count']) / $previous['endorsements_count']) * 100, 1);
        } elseif ($current && $current['endorsements_count'] > 0) {
            $volumeGrowth = 100;
        }

        $commissionGrowth = 0;
        if ($previous && $previous['commission_amount'] > 0) {
            $commissionGrowth = round((($current['commission_amount'] - $previous['commission_amount']) / $previous['commission_amount']) * 100, 1);
        } elseif ($current && $current['commission_amount'] > 0) {
            $commissionGrowth = 100;
        }

        return [
            'period_label' => $periodLabel,
            'items' => $items,
            'labels' => $items->pluck('label')->values()->toArray(),
            'volumes' => $items->pluck('endorsements_count')->values()->toArray(),
            'commissions' => $items->pluck('commission_amount')->values()->toArray(),
            'summary' => [
                'totalEndorsements' => $items->sum('endorsements_count'),
                'totalCommission' => $items->sum('commission_amount'),
                'avgEndorsements' => $items->count() > 0 ? round($items->avg('endorsements_count'), 1) : 0,
                'avgCommission' => $items->count() > 0 ? (float) $items->avg('commission_amount') : 0,
                'volumeGrowth' => $volumeGrowth,
                'commissionGrowth' => $commissionGrowth,
                'growthLabel' => 'MoM Komisi',
                'unitLabel' => '/bln',
            ],
        ];
    }

    /**
     * Build weekly trend dataset.
     */
    protected function buildWeeklyTrend(int $weeks = 8): array
    {
        $items = collect();
        for ($i = $weeks - 1; $i >= 0; $i--) {
            $start = now()->subWeeks($i)->startOfWeek();
            $end = now()->subWeeks($i)->endOfWeek();

            $endorsementsCount = Endorsement::whereBetween('created_at', [$start, $end])->count();
            $commissionAmount = (float) Commission::whereBetween('created_at', [$start, $end])->sum('commission_amount');
            $feeAmount = (float) Commission::whereBetween('created_at', [$start, $end])->sum('endorsement_fee');

            if ($start->month === $end->month) {
                $rangeLabel = $start->translatedFormat('d').' - '.$end->translatedFormat('d M');
            } else {
                $rangeLabel = $start->translatedFormat('d M').' - '.$end->translatedFormat('d M');
            }

            $items->put($start->format('Y-\WW'), [
                'label' => $rangeLabel,
                'short_label' => $rangeLabel,
                'total' => $endorsementsCount,
                'endorsements_count' => $endorsementsCount,
                'commission_amount' => $commissionAmount,
                'fee_amount' => $feeAmount,
            ]);
        }

        $current = $items->last();
        $previous = $items->count() >= 2 ? $items->slice(-2, 1)->first() : null;

        $volumeGrowth = 0;
        if ($previous && $previous['endorsements_count'] > 0) {
            $volumeGrowth = round((($current['endorsements_count'] - $previous['endorsements_count']) / $previous['endorsements_count']) * 100, 1);
        } elseif ($current && $current['endorsements_count'] > 0) {
            $volumeGrowth = 100;
        }

        $commissionGrowth = 0;
        if ($previous && $previous['commission_amount'] > 0) {
            $commissionGrowth = round((($current['commission_amount'] - $previous['commission_amount']) / $previous['commission_amount']) * 100, 1);
        } elseif ($current && $current['commission_amount'] > 0) {
            $commissionGrowth = 100;
        }

        return [
            'period_label' => '8 Minggu',
            'items' => $items,
            'labels' => $items->pluck('label')->values()->toArray(),
            'volumes' => $items->pluck('endorsements_count')->values()->toArray(),
            'commissions' => $items->pluck('commission_amount')->values()->toArray(),
            'summary' => [
                'totalEndorsements' => $items->sum('endorsements_count'),
                'totalCommission' => $items->sum('commission_amount'),
                'avgEndorsements' => $items->count() > 0 ? round($items->avg('endorsements_count'), 1) : 0,
                'avgCommission' => $items->count() > 0 ? (float) $items->avg('commission_amount') : 0,
                'volumeGrowth' => $volumeGrowth,
                'commissionGrowth' => $commissionGrowth,
                'growthLabel' => 'WoW Komisi',
                'unitLabel' => '/mgg',
            ],
        ];
    }

    /**
     * Build daily trend dataset (30 days).
     */
    protected function buildDailyTrend(int $days = 30): array
    {
        $items = collect();
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $start = $date->copy()->startOfDay();
            $end = $date->copy()->endOfDay();

            $endorsementsCount = Endorsement::whereBetween('created_at', [$start, $end])->count();
            $commissionAmount = (float) Commission::whereBetween('created_at', [$start, $end])->sum('commission_amount');
            $feeAmount = (float) Commission::whereBetween('created_at', [$start, $end])->sum('endorsement_fee');

            $items->put($date->format('Y-m-d'), [
                'label' => $date->translatedFormat('d M'),
                'short_label' => $date->format('d'),
                'total' => $endorsementsCount,
                'endorsements_count' => $endorsementsCount,
                'commission_amount' => $commissionAmount,
                'fee_amount' => $feeAmount,
            ]);
        }

        $firstHalf = $items->take(15);
        $secondHalf = $items->skip(15);

        $firstCommission = $firstHalf->sum('commission_amount');
        $secondCommission = $secondHalf->sum('commission_amount');

        $commissionGrowth = 0;
        if ($firstCommission > 0) {
            $commissionGrowth = round((($secondCommission - $firstCommission) / $firstCommission) * 100, 1);
        } elseif ($secondCommission > 0) {
            $commissionGrowth = 100;
        }

        $firstVolume = $firstHalf->sum('endorsements_count');
        $secondVolume = $secondHalf->sum('endorsements_count');

        $volumeGrowth = 0;
        if ($firstVolume > 0) {
            $volumeGrowth = round((($secondVolume - $firstVolume) / $firstVolume) * 100, 1);
        } elseif ($secondVolume > 0) {
            $volumeGrowth = 100;
        }

        return [
            'period_label' => '30 Hari',
            'items' => $items,
            'labels' => $items->pluck('label')->values()->toArray(),
            'volumes' => $items->pluck('endorsements_count')->values()->toArray(),
            'commissions' => $items->pluck('commission_amount')->values()->toArray(),
            'summary' => [
                'totalEndorsements' => $items->sum('endorsements_count'),
                'totalCommission' => $items->sum('commission_amount'),
                'avgEndorsements' => $items->count() > 0 ? round($items->avg('endorsements_count'), 1) : 0,
                'avgCommission' => $items->count() > 0 ? (float) $items->avg('commission_amount') : 0,
                'volumeGrowth' => $volumeGrowth,
                'commissionGrowth' => $commissionGrowth,
                'growthLabel' => '15h Tren Komisi',
                'unitLabel' => '/hari',
            ],
        ];
    }
}
