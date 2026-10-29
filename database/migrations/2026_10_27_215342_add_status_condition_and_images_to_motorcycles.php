<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->string('availability_status')->default('available')->after('category');
            $table->string('condition')->default('new')->after('availability_status');
            $table->json('additional_images')->nullable()->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropColumn(['availability_status', 'condition', 'additional_images']);
        });
    }
};
