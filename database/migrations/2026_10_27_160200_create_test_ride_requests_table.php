<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Test-ride requests submitted from the homepage final CTA mini-form.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('test_ride_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->foreignId('motorcycle_id')->nullable()->constrained()->nullOnDelete();
            $table->string('preferred_showroom')->nullable();
            $table->string('status')->default('new'); // new, contacted, scheduled, closed
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('test_ride_requests');
    }
};
