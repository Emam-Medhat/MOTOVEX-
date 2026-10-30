<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends the existing promotions table (no duplicate system) with an
 * optional motorcycle target and optional, admin-entered discount values.
 * Discount values are never derived/invented by the application.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('motorcycle_id')->nullable()->after('brand_id')->index();
            $table->string('discount_type')->nullable()->after('description'); // percentage | fixed
            $table->decimal('discount_value', 12, 2)->nullable()->after('discount_type');
        });
    }

    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['motorcycle_id', 'discount_type', 'discount_value']);
        });
    }
};
