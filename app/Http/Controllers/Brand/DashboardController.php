<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = request()->user();
        $brand = $user->brand ?? Brand::where('pic_email', $user->email)->first();

        if ($brand && ! $brand->user_id) {
            $brand->update(['user_id' => $user->id]);
        }

        if (! $brand) {
            $brand = Brand::create([
                'user_id' => $user->id,
                'name' => $user->name,
                'pic_name' => $user->name,
                'pic_email' => $user->email,
                'is_active' => true,
            ]);
        }

        return view('brand.dashboard', [
            'brand' => $brand,
            'stats' => [
                'totalCampaigns' => $brand->campaigns()->count(),
                'totalEndorsements' => $brand->endorsements()->count(),
                'pendingProducts' => $brand->products()->where('verification_status', 'pending')->count(),
                'unreadNotifications' => $user->unreadNotifications()->count(),
            ],
            'recentCampaigns' => $brand->campaigns()->latest()->limit(5)->get(),
            'recentEndorsements' => $brand->endorsements()->with(['kolProfile.user', 'campaign'])->latest()->limit(5)->get(),
            'notifications' => $user->notifications()->latest()->limit(5)->get(),
        ]);
    }
}
