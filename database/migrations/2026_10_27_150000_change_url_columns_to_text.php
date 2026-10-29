<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->text('image_url')->nullable()->change();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->text('logo_url')->nullable()->change();
            $table->text('website_url')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->string('image_url')->nullable()->change();
        });

        Schema::table('brands', function (Blueprint $table) {
            $table->string('logo_url')->nullable()->change();
            $table->string('website_url')->nullable()->change();
        });
    }
};
