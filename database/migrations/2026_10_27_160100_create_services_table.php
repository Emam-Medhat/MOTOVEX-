<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Ownership services shown in the bento-grid section
 * (Test Rides, Financing, Service, Parts, Warranty, Customer Care).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon')->default('sparkles'); // Lucide icon name
            $table->text('description');
            $table->string('cta_label')->nullable();   // e.g. "Book now"
            $table->string('cta_route')->nullable();  // route name or url
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
