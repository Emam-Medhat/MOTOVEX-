<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->firstOrFail();
        $dataEntryRole = Role::where('slug', 'data_entry')->firstOrFail();
        $salesRole = Role::where('slug', 'sales_agent')->firstOrFail();
        $customerRole = Role::where('slug', 'customer')->firstOrFail();

        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@motovex.com',
            'phone' => '+971500000000',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $admin->roles()->attach($adminRole->id);

        $neoEntry = User::create([
            'name' => 'Data Entry NÉO',
            'email' => 'neo@motovex.com',
            'phone' => '+971500000010',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $neoEntry->roles()->attach($dataEntryRole->id);
        $neoEntry->brands()->attach(
            Brand::where('slug', 'neo')->firstOrFail()->id,
            ['role' => 'data_entry']
        );

        $atlasEntry = User::create([
            'name' => 'Data Entry Atlas',
            'email' => 'atlas@motovex.com',
            'phone' => '+971500000011',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $atlasEntry->roles()->attach($dataEntryRole->id);
        $atlasEntry->brands()->attach(
            Brand::where('slug', 'atlas')->firstOrFail()->id,
            ['role' => 'data_entry']
        );

        $multiEntry = User::create([
            'name' => 'Data Entry Multi',
            'email' => 'multi@motovex.com',
            'phone' => '+971500000012',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $multiEntry->roles()->attach($dataEntryRole->id);
        $multiEntry->brands()->attach([
            Brand::where('slug', 'vayu')->firstOrFail()->id => ['role' => 'data_entry'],
            Brand::where('slug', 'aurum')->firstOrFail()->id => ['role' => 'data_entry'],
        ]);

        $salesAgent = User::create([
            'name' => 'Sales Agent Dubai',
            'email' => 'sales@motovex.com',
            'phone' => '+971500000020',
            'country' => 'United Arab Emirates',
            'city' => 'Dubai',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);
        $salesAgent->roles()->attach($salesRole->id);

        $customer = User::create([
            'name' => 'Demo Customer',
            'email' => 'customer@motovex.com',
            'phone' => '+971500000030',
            'country' => 'United Arab Emirates',
            'city' => 'Dubai',
            'preferred_brand_id' => Brand::where('slug', 'neo')->value('id'),
            'password' => Hash::make('password123'),
            'is_active' => true,
            'newsletter_opted_in' => true,
        ]);
        $customer->roles()->attach($customerRole->id);

        // Deactivated account used to demonstrate that suspended logins are blocked.
        $suspended = User::create([
            'name' => 'Suspended Customer',
            'email' => 'suspended@motovex.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);
        $suspended->roles()->attach($customerRole->id);
    }
}
