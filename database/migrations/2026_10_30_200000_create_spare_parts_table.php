<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Spare Parts catalogue.
 * Supports Admin and Merchant ownership, brand/category linkage,
 * motorcycle compatibility, image uploads, and full publication workflow.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();

            // Ownership — server-side only, never trust client input
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Brand linkage (optional — some parts are universal)
            $table->foreignId('brand_id')
                  ->nullable()
                  ->constrained('brands')
                  ->nullOnDelete();

            // Identity
            $table->string('name');
            $table->string('slug')->unique()->index();
            $table->string('sku')->nullable()->index();   // part number / SKU
            $table->string('category')->nullable()->index();

            // Content
            $table->text('description')->nullable();
            $table->text('compatibility_notes')->nullable(); // free-text notes on compatible models

            // Pricing
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->default('USD');

            // Stock
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('availability_status', 30)->default('in_stock')->index();
            // Values: in_stock | low_stock | out_of_stock | discontinued

            // Images
            $table->string('image_path')->nullable();         // main uploaded image
            $table->json('additional_images')->nullable();    // gallery (JSON array of paths)

            // Publication
            $table->boolean('is_published')->default(false)->index();
            // Admin controls publication; Merchants submit, Admin publishes.

            $table->timestamps();
        });

        // Pivot: which motorcycle models this part is compatible with
        Schema::create('spare_part_motorcycle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spare_part_id')
                  ->constrained('spare_parts')
                  ->cascadeOnDelete();
            $table->foreignId('motorcycle_id')
                  ->constrained('motorcycles')
                  ->cascadeOnDelete();
            $table->unique(['spare_part_id', 'motorcycle_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spare_part_motorcycle');
        Schema::dropIfExists('spare_parts');
    }
};
