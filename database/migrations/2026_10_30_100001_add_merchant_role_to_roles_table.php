<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Add the 'merchant' role.
     * This is the public-facing name for users who sell motorcycles.
     * Previously, the system used 'data_entry' as the slug for this type of user
     * when self-registered. We introduce 'merchant' as the dedicated public-registration
     * role; existing data_entry staff created by admins are unaffected.
     */
    public function up(): void
    {
        DB::table('roles')->insertOrIgnore([
            'name'        => 'Merchant',
            'slug'        => 'merchant',
            'description' => 'Trader / dealer account. Requires admin approval before accessing the merchant dashboard.',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('roles')->where('slug', 'merchant')->delete();
    }
};
