<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showrooms', function (Blueprint $row) {
            $row->id();
            $row->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $row->string('name');
            $row->string('slug')->unique();
            $row->string('location');
            $row->string('phone')->nullable();
            $row->string('email')->nullable();
            $row->text('opening_hours')->nullable();
            $row->string('image_url')->nullable();
            $row->boolean('is_active')->default(true)->index();
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showrooms');
    }
};
