<?php

namespace Database\Seeders;

use App\Models\Niche;
use Illuminate\Database\Seeder;

class NicheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niches = [
            'Lifestyle',
            'Beauty & Skincare',
            'Gaming & Esports',
            'Food & Beverage',
            'Travel & Tourism',
            'Tech & Gadgets',
            'Fashion & Style',
            'Health & Fitness',
            'Education & Self Development',
            'Parenting & Family',
            'Automotive',
            'Sports',
            'Entertainment & Comedy',
            'Finance & Business',
        ];

        foreach ($niches as $nicheName) {
            Niche::firstOrCreate(['name' => $nicheName], [
                'name' => $nicheName,
                'is_active' => true,
            ]);
        }
    }
}
