<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Extend order_items to support spare parts alongside motorcycles.
 *
 * Changes:
 *  - product_type: 'motorcycle' | 'spare_part'  (defaults to 'motorcycle' for existing rows)
 *  - spare_part_id: nullable FK to spare_parts
 *  - product_snapshot: canonical text snapshot (replaces motorcycle_snapshot going forward; kept for compat)
 *  - sku_snapshot: part SKU at time of purchase
 *  - fulfillment_notes: merchant notes on this item
 *  - rejection_reason: mandatory when status = rejected
 *  - tracking_number: shipping tracking
 *  - shipped_at / delivered_at: timestamps
 *  - status_updated_by: who last changed the status
 *  - status_updated_at: when status last changed
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Product type discriminator
            $table->string('product_type', 30)->default('motorcycle')->after('order_id');

            // Spare part FK (nullable — motorcycle items use motorcycle_id)
            $table->foreignId('spare_part_id')
                  ->nullable()
                  ->after('motorcycle_id')
                  ->constrained('spare_parts')
                  ->nullOnDelete();

            // Generic product name snapshot (spare parts don't have motorcycle_snapshot)
            $table->string('product_snapshot')->nullable()->after('motorcycle_snapshot');

            // SKU captured at purchase time
            $table->string('sku_snapshot')->nullable()->after('product_snapshot');

            // Fulfillment metadata
            $table->text('fulfillment_notes')->nullable()->after('status');
            $table->string('rejection_reason')->nullable()->after('fulfillment_notes');
            $table->string('tracking_number')->nullable()->after('rejection_reason');
            $table->timestamp('shipped_at')->nullable()->after('tracking_number');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');

            // Audit: who changed status and when
            $table->foreignId('status_updated_by')
                  ->nullable()
                  ->after('delivered_at')
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('status_updated_at')->nullable()->after('status_updated_by');
        });

        // Backfill: copy motorcycle_snapshot → product_snapshot for all existing rows
        DB::statement('UPDATE order_items SET product_snapshot = motorcycle_snapshot WHERE product_snapshot IS NULL');
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['spare_part_id']);
            $table->dropForeign(['status_updated_by']);
            $table->dropColumn([
                'product_type', 'spare_part_id', 'product_snapshot', 'sku_snapshot',
                'fulfillment_notes', 'rejection_reason', 'tracking_number',
                'shipped_at', 'delivered_at', 'status_updated_by', 'status_updated_at',
            ]);
        });
    }
};
