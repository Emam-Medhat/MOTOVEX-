<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Motorcycle;
use Illuminate\Database\Seeder;

/**
 * Motorcycles across the four segments, each referencing a transparent
 * placeholder bike image in public/images/bikes/<slug>.svg that can be
 * swapped for real PNGs without touching code.
 */
class MotorcycleSeeder extends Seeder
{
    public function run(): void
    {
        $neo     = Brand::where('slug', 'neo')->firstOrFail();
        $atlas   = Brand::where('slug', 'atlas')->firstOrFail();
        $vayu    = Brand::where('slug', 'vayu')->firstOrFail();
        $aurum   = Brand::where('slug', 'aurum')->firstOrFail();

        $motorcycles = [
            // ── NÉO · Urban Mobility ───────────────────────────────────────
            [
                'brand_id'          => $neo->id,
                'model_name'        => 'NÉO S1',
                'slug'              => 'neo-s1',
                'short_description' => 'Compact electric-style commuter for the daily sprint.',
                'description'       => 'Fictional urban machine with a light, reactive chassis and instant torque for dense-city rhythm. Specs are illustrative only.',
                'image_url'         => '/images/bikes/neo-s1.svg',
                'engine_capacity'   => 350.00,
                'horsepower'        => 48,
                'torque'            => 42,
                'transmission'      => 'Automatic',
                'price'             => 6900.00,
                'currency'          => 'USD',
                'year'              => 2026,
                'category'          => 'Urban Mobility',
                'is_published'      => true,
                'is_featured'       => true,
            ],
            [
                'brand_id'          => $neo->id,
                'model_name'        => 'NÉO X',
                'slug'              => 'neo-x',
                'short_description' => 'Agile naked for canyon-to-city loops.',
                'description'       => 'Fictional lightweight streetfighter with balanced power delivery for everyday aggression. Demo only.',
                'image_url'         => '/images/bikes/neo-x.svg',
                'engine_capacity'   => 420.00,
                'horsepower'        => 58,
                'torque'            => 46,
                'transmission'      => '6-speed',
                'price'             => 8400.00,
                'currency'          => 'USD',
                'year'              => 2026,
                'category'          => 'Urban Mobility',
                'is_published'      => true,
                'is_featured'       => false,
            ],

            // ── ATLAS · Adventure & Touring ───────────────────────────────
            [
                'brand_id'          => $atlas->id,
                'model_name'        => 'ATLAS Traverse 1200',
                'slug'              => 'atlas-traverse-1200',
                'short_description' => 'Long-range adventure platform for mixed terrain.',
                'description'       => 'Fictional adventure motorcycle with touring-grade comfort and generous range across desert and mountain. All figures are illustrative.',
                'image_url'         => '/images/bikes/atlas-traverse-1200.svg',
                'engine_capacity'   => 1198.00,
                'horsepower'        => 148,
                'torque'            => 149,
                'transmission'      => '6-speed',
                'price'             => 26800.00,
                'currency'          => 'USD',
                'year'              => 2026,
                'category'          => 'Adventure & Touring',
                'is_published'      => true,
                'is_featured'       => true,
            ],
            [
                'brand_id'          => $atlas->id,
                'model_name'        => 'ATLAS Ridge 850',
                'slug'              => 'atlas-ridge-850',
                'short_description' => 'Mid-weight adventure with a focused chassis.',
                'description'       => 'Fictional mid-capacity adventure model for two-up touring and lighter trails. Demo only.',
                'image_url'         => '/images/bikes/atlas-ridge-850.svg',
                'engine_capacity'   => 853.00,
                'horsepower'        => 95,
                'torque'            => 92,
                'transmission'      => '6-speed',
                'price'             => 13950.00,
                'currency'          => 'USD',
                'year'              => 2025,
                'category'          => 'Adventure & Touring',
                'is_published'      => true,
                'is_featured'       => false,
            ],

            // ── VAYU · Performance ────────────────────────────────────────
            [
                'brand_id'          => $vayu->id,
                'model_name'        => 'VAYU R1 1100',
                'slug'              => 'vayu-r1-1100',
                'short_description' => 'Flagship superbike with a cinematic silhouette.',
                'description'       => 'Fictional superbike with track-bred electronics, wing-led aero and a razor-sharp chassis. The hero of the line-up. Specs illustrative only.',
                'image_url'         => '/images/bikes/vayu-r1-1100.svg',
                'engine_capacity'   => 1099.00,
                'horsepower'        => 215,
                'torque'            => 124,
                'transmission'      => '6-speed',
                'price'             => 42900.00,
                'currency'          => 'USD',
                'year'              => 2026,
                'category'          => 'Performance',
                'is_published'      => true,
                'is_featured'       => true,
            ],
            [
                'brand_id'          => $vayu->id,
                'model_name'        => 'VAYU Street 790',
                'slug'              => 'vayu-street-790',
                'short_description' => 'Lightweight street weapon with urgent throttle.',
                'description'       => 'Fictional streetfighter with urgent response and a flickable chassis for canyon carving. Demo only.',
                'image_url'         => '/images/bikes/vayu-street-790.svg',
                'engine_capacity'   => 799.00,
                'horsepower'        => 118,
                'torque'            => 87,
                'transmission'      => '6-speed',
                'price'             => 11800.00,
                'currency'          => 'USD',
                'year'              => 2026,
                'category'          => 'Performance',
                'is_published'      => true,
                'is_featured'       => false,
            ],

            // ── AURUM · Premium ───────────────────────────────────────────
            [
                'brand_id'          => $aurum->id,
                'model_name'        => 'AURUM Grandura',
                'slug'              => 'aurum-grandura',
                'short_description' => 'Calm grand tourer for long, quiet miles.',
                'description'       => 'Fictional touring motorcycle with comfort-first geometry and hand-finished detailing. The flagship of the premium line. Demo only.',
                'image_url'         => '/images/bikes/aurum-grandura.svg',
                'engine_capacity'   => 1160.00,
                'horsepower'        => 125,
                'torque'            => 125,
                'transmission'      => '6-speed',
                'price'             => 32900.00,
                'currency'          => 'USD',
                'year'              => 2026,
                'category'          => 'Premium',
                'is_published'      => true,
                'is_featured'       => true,
            ],
            [
                'brand_id'          => $aurum->id,
                'model_name'        => 'AURUM Heritage 650',
                'slug'              => 'aurum-heritage-650',
                'short_description' => 'Modern classic with restrained detailing.',
                'description'       => 'Fictional classic-inspired motorcycle with modern engineering restraint for the lifestyle rider. Demo only.',
                'image_url'         => '/images/bikes/aurum-heritage-650.svg',
                'engine_capacity'   => 649.00,
                'horsepower'        => 54,
                'torque'            => 59,
                'transmission'      => '5-speed',
                'price'             => 14200.00,
                'currency'          => 'USD',
                'year'              => 2025,
                'category'          => 'Premium',
                'is_published'      => true,
                'is_featured'       => false,
            ],
        ];

        foreach ($motorcycles as $motorcycle) {
            Motorcycle::create($motorcycle);
        }
    }
}
