<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BrandSeeder::class,
            MotorcycleSeeder::class,
            ServiceSeeder::class,
            UserSeeder::class,
            EnquirySeeder::class,
            PromotionSeeder::class,
            ShowroomSeeder::class,
        ]);
    }
}
