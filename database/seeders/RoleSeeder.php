<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin',       'slug' => 'admin',       'description' => 'Full platform administrator. Manages all brands, content and users.'],
            ['name' => 'Data Entry',  'slug' => 'data_entry',  'description' => 'Manages catalogue content for the brands assigned to them only.'],
            ['name' => 'Sales Agent', 'slug' => 'sales_agent', 'description' => 'Showroom staff. Tracks enquiries and test rides for their assigned showroom.'],
            ['name' => 'Customer',    'slug' => 'customer',    'description' => 'Public customer account. Browses the site and manages their own enquiries and test rides.'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['slug' => $role['slug']], $role);
        }
    }
}
