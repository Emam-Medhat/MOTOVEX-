<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

/**
 * Cinematic promotions for the homepage banner, including one
 * time-limited offer that powers the countdown timer.
 */
class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $vayu = Brand::where('slug', 'vayu')->first();
        $aurum = Brand::where('slug', 'aurum')->first();

        $promotions = [
            [
                'brand_id'   => $vayu?->id,
                'title'      => 'VAYU Apex Days — 3.9% Financing',
                'slug'       => 'vayu-apex-days',
                'description'=> 'For a limited window, ride home a VAYU R1 1100 with exclusive 3.9% financing and a complimentary track-day package. Offer ends soon.',
                'image_url'  => '/images/bikes/vayu-r1-1100.svg',
                'start_date' => now(),
                'end_date'   => now()->addDays(21),
                'is_active'  => true,
            ],
            [
                'brand_id'   => $aurum?->id,
                'title'      => 'AURUM Heritage Trade-In Bonus',
                'slug'       => 'aurum-trade-in-bonus',
                'description'=> 'Trade in your current machine toward an AURUM Grandura and receive a premium accessory pack on us. Crafted, end to end.',
                'image_url'  => '/images/bikes/aurum-grandura.svg',
                'start_date' => now()->subDays(5),
                'end_date'   => now()->addMonths(2),
                'is_active'  => true,
            ],
            [
                'brand_id'   => null,
                'title'      => 'Winter Service Special',
                'slug'       => 'winter-service-special',
                'description'=> 'Full diagnostic check and oil change at any group service center this season. Keep every machine at its peak.',
                'image_url'  => '/images/bikes/atlas-traverse-1200.svg',
                'start_date' => now()->subDays(10),
                'end_date'   => now()->addMonths(1),
                'is_active'  => true,
            ],
        ];

        foreach ($promotions as $promo) {
            Promotion::create($promo);
        }
    }
}
