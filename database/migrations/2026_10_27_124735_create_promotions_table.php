<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('promotions', function (Blueprint $row) {
            $row->id();
            $row->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $row->string('title');
            $row->string('slug')->unique();
            $row->text('description');
            $row->string('image_url')->nullable();
            $row->date('start_date')->nullable();
            $row->date('end_date')->nullable();
            $row->boolean('is_active')->default(true)->index();
            $row->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
