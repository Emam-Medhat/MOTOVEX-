<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

/**
 * Ownership services rendered as a bento-grid on the homepage.
 * Icon names map to Lucide icons.
 */
class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title'       => 'Test Rides',
                'slug'        => 'test-rides',
                'icon'        => 'bike',
                'description' => 'Book a guided ride on any model across our Middle East showrooms and feel the difference before you commit.',
                'cta_label'   => 'Book a ride',
                'cta_route'   => '#final-cta',
                'sort_order'  => 1,
                'is_active'   => true,
            ],
            [
                'title'       => 'Financing',
                'slug'        => 'financing',
                'icon'        => 'wallet',
                'description' => 'Tailored plans and competitive rates through our partner banks, with approvals in as little as 48 hours.',
                'cta_label'   => 'Explore plans',
                'cta_route'   => '#final-cta',
                'sort_order'  => 2,
                'is_active'   => true,
            ],
            [
                'title'       => 'Service & Maintenance',
                'slug'        => 'service-maintenance',
                'icon'        => 'wrench',
                'description' => 'Manufacturer-trained technicians, genuine diagnostics and scheduled care to keep every machine at its peak.',
                'cta_label'   => 'Book service',
                'cta_route'   => '#final-cta',
                'sort_order'  => 3,
                'is_active'   => true,
            ],
            [
                'title'       => 'Parts & Accessories',
                'slug'        => 'parts-accessories',
                'icon'        => 'package',
                'description' => 'Genuine parts and a curated catalogue of performance, touring and lifestyle accessories for every rider.',
                'cta_label'   => 'Browse parts',
                'cta_route'   => '#final-cta',
                'sort_order'  => 4,
                'is_active'   => true,
            ],
            [
                'title'       => 'Warranty',
                'slug'        => 'warranty',
                'icon'        => 'shield-check',
                'description' => 'Comprehensive warranty coverage with extended protection plans available across the full range.',
                'cta_label'   => 'Learn more',
                'cta_route'   => '#final-cta',
                'sort_order'  => 5,
                'is_active'   => true,
            ],
            [
                'title'       => 'Customer Care',
                'slug'        => 'customer-care',
                'icon'        => 'headset',
                'description' => 'Dedicated support in your language, available across the region — from first question to long after purchase.',
                'cta_label'   => 'Contact us',
                'cta_route'   => route('contact'),
                'sort_order'  => 6,
                'is_active'   => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
