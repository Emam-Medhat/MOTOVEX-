<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

/**
 * Four fictional brands — one per market segment.
 * Each carries its own accent colour which re-themes the homepage.
 * Swap names/colours here (and config/group.php) to rebrand the whole site.
 */
class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            [
                'name'         => 'NÉO',
                'slug'         => 'neo',
                'segment'      => 'Urban Mobility',
                'tagline'      => 'Move the city on your terms',
                'accent_color' => '#1ee05a',
                'description'  => 'Fictional urban-mobility marque built for dense streets and quick getaways. Lightweight, electric-ready, instantly responsive. Demo catalogue only.',
                'logo_url'     => '/images/bikes/neo-logo.svg',
                'website_url'  => null,
                'country'      => 'United Arab Emirates',
                'sort_order'   => 1,
                'is_published' => true,
            ],
            [
                'name'         => 'ATLAS',
                'slug'         => 'atlas',
                'segment'      => 'Adventure & Touring',
                'tagline'      => 'Every road is a checkpoint',
                'accent_color' => '#f59e0b',
                'description'  => 'Fictional adventure marque engineered for long-distance confidence across desert highways and mountain passes. Touring-first geometry, generous range. Demo catalogue only.',
                'logo_url'     => '/images/bikes/atlas-logo.svg',
                'website_url'  => null,
                'country'      => 'Germany',
                'sort_order'   => 2,
                'is_published' => true,
            ],
            [
                'name'         => 'VAYU',
                'slug'         => 'vayu',
                'segment'      => 'Performance',
                'tagline'      => 'Engineered for the apex',
                'accent_color' => '#ef4444',
                'description'  => 'Fictional performance marque with track-bred electronics and razor-sharp chassis dynamics. Born to hunt the apex. Demo catalogue only.',
                'logo_url'     => '/images/bikes/vayu-logo.svg',
                'website_url'  => null,
                'country'      => 'Italy',
                'sort_order'   => 3,
                'is_published' => true,
            ],
            [
                'name'         => 'AURUM',
                'slug'         => 'aurum',
                'segment'      => 'Premium',
                'tagline'      => 'Crafted for the few',
                'accent_color' => '#d4af37',
                'description'  => 'Fictional premium marque blending classic-inspired silhouettes with modern engineering restraint. Hand-finished detailing, calm authority. Demo catalogue only.',
                'logo_url'     => '/images/bikes/aurum-logo.svg',
                'website_url'  => null,
                'country'      => 'United Kingdom',
                'sort_order'   => 4,
                'is_published' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
}
