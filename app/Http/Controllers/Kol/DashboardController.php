<?php

namespace App\Http\Controllers\Kol;

use App\Http\Controllers\Controller;
use App\Models\KolProfile;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $profile = request()->user()->kolProfile()->first();

        if (! $profile) {
            abort(404, 'Profil KOL belum tersedia.');
        }

        $profile->load('user');

        $endorsements = $profile->endorsements();
        $activeStatuses = ['assigned', 'in_progress', 'content_submitted', 'content_approved'];

        // Top 3 Leaders for the month for Gamification Highlight
        $topLeaders = KolProfile::where('status', 'aktif')
            ->with(['user:id,name', 'tier:id,name'])
            ->withCount([
                'endorsements as completed_endorsements_count' => function ($q) {
                    $q->whereIn('status', ['selesai', 'completed', 'content_approved', 'approved'])
                        ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()]);
                },
            ])
            ->get()
            ->sortByDesc('completed_endorsements_count')
            ->take(3)
            ->values();

        return view('kol.dashboard', [
            'profile' => $profile,
            'stats' => [
                'activeEndorsements' => (clone $endorsements)->whereIn('status', $activeStatuses)->count(),
                'monthCommission' => $profile->commissions()->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('commission_amount'),
                'pendingTasks' => (clone $endorsements)->whereIn('status', ['assigned', 'in_progress', 'content_rejected'])->count(),
                'unreadNotifications' => request()->user()->notifications()->where('is_read', false)->count(),
            ],
            'recentEndorsements' => (clone $endorsements)->with(['campaign.brand:id,name'])->latest()->limit(5)->get(),
            'upcomingEndorsements' => (clone $endorsements)->with(['campaign.brand:id,name'])->whereDate('deadline', '>=', now()->toDateString())->whereNot('status', 'selesai')->orderBy('deadline')->limit(5)->get(),
            'notifications' => request()->user()->notifications()->latest()->limit(5)->get(),
            'topLeaders' => $topLeaders,
        ]);
    }
}
