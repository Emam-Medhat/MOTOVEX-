<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            // Custom key-value specs (replaces the individual engine/hp/torque/etc. fields visually)
            $table->json('specs')->nullable()->after('description');
            // Local uploaded image path (replaces image_url for uploaded files)
            $table->string('image_path')->nullable()->after('image_url');
        });
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropColumn(['specs', 'image_path']);
        });
    }
};
