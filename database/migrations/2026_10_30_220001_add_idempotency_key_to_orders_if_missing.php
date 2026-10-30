<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ensure orders.idempotency_key column exists (it was in $fillable but
 * a separate migration added it; this is a safe guard migration).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('orders', 'idempotency_key')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('idempotency_key')->nullable()->unique()->after('admin_notes');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'idempotency_key')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('idempotency_key');
            });
        }
    }
};
