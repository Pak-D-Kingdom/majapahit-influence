<?php

namespace App\Http\Controllers\Superadmin;

use App\Models\Campaign;
use App\Models\Commission;
use App\Models\Endorsement;
use App\Models\KolProfile;
use App\Models\KolRegistration;
use App\Models\Notification;
use App\Http\Controllers\Controller;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $period = CarbonPeriod::create(now()->subMonths(5)->startOfMonth(), '1 month', now()->startOfMonth());
        $trend = collect($period)->mapWithKeys(fn (Carbon $month): array => [
            $month->format('Y-m') => [
                'label' => $month->translatedFormat('M'),
                'total' => Endorsement::whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])->count(),
            ],
        ]);

        $deadlineEnd = now()->addDays(7)->endOfDay();

        return view('superadmin.dashboard', [
            'stats' => [
                'activeKols' => KolProfile::active()->count(),
                'activeEndorsements' => Endorsement::whereIn('status', ['assigned', 'in_progress', 'content_submitted'])->count(),
                'pendingRegistrations' => KolRegistration::where('status', 'pending_review')->count(),
                'pendingDisbursements' => Commission::whereIn('status', ['pending', 'approved'])->count(),
                'unpaidCommission' => Commission::whereIn('status', ['pending', 'approved'])->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount'),
            ],
            'endorsementTrend' => $trend,
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
}
