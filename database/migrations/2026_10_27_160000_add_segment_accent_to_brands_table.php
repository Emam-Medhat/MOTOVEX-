<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds segment / tagline / accent colour to brands so each brand can
 * own a market segment and re-theme the homepage via CSS variables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->string('segment')->nullable()->after('country');
            $table->string('tagline')->nullable()->after('segment');
            $table->string('accent_color', 20)->nullable()->after('tagline');
            $table->integer('sort_order')->default(0)->after('accent_color');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropIndex(['sort_order']);
            $table->dropColumn(['segment', 'tagline', 'accent_color', 'sort_order']);
        });
    }
};
