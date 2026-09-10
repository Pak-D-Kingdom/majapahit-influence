<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\View\View;

class CampaignController extends Controller
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

        $campaigns = $brand->campaigns()->latest()->paginate(10);

        return view('brand.campaigns.index', [
            'campaigns' => $campaigns,
        ]);
    }
}
