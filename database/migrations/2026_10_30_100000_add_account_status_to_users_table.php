<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add account_status column to users table.
     *
     * Values: active | pending | rejected
     * - Customers and admin-created accounts → active
     * - Self-registered merchants → pending until admin approves
     * - Rejected merchant applications → rejected
     *
     * Existing active users default to 'active'.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('account_status', 20)
                  ->default('active')
                  ->after('is_active')
                  ->comment('active | pending | rejected');

            $table->timestamp('approved_at')->nullable()->after('account_status');
            $table->unsignedBigInteger('approved_by')->nullable()->after('approved_at');

            $table->index('account_status');
        });

        // Backfill: any existing inactive accounts should stay as-is.
        // All existing users are considered active in the new scheme.
        DB::statement("UPDATE users SET account_status = 'active'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['account_status']);
            $table->dropColumn(['account_status', 'approved_at', 'approved_by']);
        });
    }
};
