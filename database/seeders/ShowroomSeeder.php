<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Showroom;
use Illuminate\Database\Seeder;

/**
 * Showrooms across Middle East cities (placeholder locations).
 */
class ShowroomSeeder extends Seeder
{
    public function run(): void
    {
        $neo   = Brand::where('slug', 'neo')->first();
        $atlas = Brand::where('slug', 'atlas')->first();
        $vayu  = Brand::where('slug', 'vayu')->first();
        $aurum = Brand::where('slug', 'aurum')->first();

        $showrooms = [
            [
                'brand_id'     => $neo?->id,
                'name'         => 'NÉO Riyadh',
                'slug'         => 'neo-riyadh',
                'location'     => 'King Fahd Road, Riyadh, Saudi Arabia',
                'phone'        => '+966 11 000 0001',
                'email'        => 'riyadh@neo.group',
                'opening_hours'=> 'Sat–Thu: 09:00 – 22:00, Fri: 16:00 – 22:00',
                'image_url'    => '/images/bikes/showroom-riyadh.svg',
                'is_active'    => true,
            ],
            [
                'brand_id'     => $atlas?->id,
                'name'         => 'ATLAS Adventure Center Dubai',
                'slug'         => 'atlas-dubai',
                'location'     => 'Sheikh Zayed Road, Dubai, UAE',
                'phone'        => '+971 4 000 0002',
                'email'        => 'dubai@atlas.group',
                'opening_hours'=> 'Mon–Sat: 08:00 – 20:00, Sun: Closed',
                'image_url'    => '/images/bikes/showroom-dubai.svg',
                'is_active'    => true,
            ],
            [
                'brand_id'     => $vayu?->id,
                'name'         => 'VAYU Performance Doha',
                'slug'         => 'vayu-doha',
                'location'     => 'Al Corniche, Doha, Qatar',
                'phone'        => '+974 4 000 0003',
                'email'        => 'doha@vayu.group',
                'opening_hours'=> 'Sat–Thu: 10:00 – 22:00, Fri: Closed',
                'image_url'    => '/images/bikes/showroom-doha.svg',
                'is_active'    => true,
            ],
            [
                'brand_id'     => $aurum?->id,
                'name'         => 'AURUM Kuwait City',
                'slug'         => 'aurum-kuwait',
                'location'     => 'Gulf Road, Kuwait City, Kuwait',
                'phone'        => '+965 2 000 0004',
                'email'        => 'kuwait@aurum.group',
                'opening_hours'=> 'Sat–Thu: 09:30 – 21:30, Fri: 16:30 – 21:30',
                'image_url'    => '/images/bikes/showroom-kuwait.svg',
                'is_active'    => true,
            ],
            [
                'brand_id'     => null,
                'name'         => 'Group Flagship Manama',
                'slug'         => 'group-manama',
                'location'     => 'Bahrain Bay, Manama, Bahrain',
                'phone'        => '+973 1 000 0005',
                'email'        => 'manama@apex.group',
                'opening_hours'=> 'Mon–Sat: 09:00 – 20:00',
                'image_url'    => '/images/bikes/showroom-manama.svg',
                'is_active'    => true,
            ],
            [
                'brand_id'     => null,
                'name'         => 'Group Hub Muscat',
                'slug'         => 'group-muscat',
                'location'     => 'Al Khuwair, Muscat, Oman',
                'phone'        => '+968 2 000 0006',
                'email'        => 'muscat@apex.group',
                'opening_hours'=> 'Sat–Thu: 09:00 – 21:00, Fri: Closed',
                'image_url'    => '/images/bikes/showroom-muscat.svg',
                'is_active'    => true,
            ],
        ];

        foreach ($showrooms as $showroom) {
            Showroom::create($showroom);
        }
    }
}
