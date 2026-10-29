<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Enhances the enquiries table to support structured purchase orders
 * alongside the existing general/test-drive/service enquiry types.
 *
 * SAFE: additive only — no existing columns removed or modified.
 * Existing enquiry records are fully preserved.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {

            // ── Ownership ─────────────────────────────────────────────────
            // Link to authenticated user when available (nullable for guest enquiries)
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('id')
                  ->constrained()
                  ->nullOnDelete();

            // ── Purchase-order reference ──────────────────────────────────
            // Human-readable, unique reference shown to customer on confirmation
            $table->string('order_reference', 30)
                  ->unique()
                  ->nullable()
                  ->after('user_id');

            // ── Motorcycle snapshot ───────────────────────────────────────
            // FK to the motorcycle (nullable — not all enquiries concern a motorcycle)
            $table->foreignId('motorcycle_id')
                  ->nullable()
                  ->after('order_reference')
                  ->constrained()
                  ->nullOnDelete();

            // Snapshot the model name at submission time so it survives renames
            $table->string('motorcycle_snapshot', 200)->nullable()->after('motorcycle_id');

            // ── Pricing snapshot ─────────────────────────────────────────
            // Unit price as seen by the customer at the time of submission
            $table->decimal('unit_price', 12, 2)->nullable()->after('motorcycle_snapshot');
            $table->string('currency', 3)->nullable()->after('unit_price');
            // Quantity (always 1 for vehicles, but recorded for completeness)
            $table->unsignedSmallInteger('quantity')->default(1)->after('currency');
            // Total calculated server-side (unit_price * quantity + delivery_fee)
            $table->decimal('total_amount', 12, 2)->nullable()->after('quantity');
            // Delivery fee (0 when collection is chosen)
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('total_amount');

            // ── Delivery / fulfillment ────────────────────────────────────
            $table->string('delivery_method', 30)->nullable()->after('delivery_fee');
            // 'delivery' or 'showroom_collection'
            $table->string('delivery_address', 400)->nullable()->after('delivery_method');
            $table->string('city', 100)->nullable()->after('delivery_address');
            $table->string('governorate', 100)->nullable()->after('city');

            // ── Payment ──────────────────────────────────────────────────
            $table->string('payment_method', 50)->nullable()->after('governorate');
            // Values: 'cash_on_delivery', 'bank_transfer', 'showroom', 'pending'
            $table->string('payment_status', 30)->default('pending')->after('payment_method');
            // Values: 'pending', 'verified', 'failed', 'refunded'
            $table->string('payment_reference', 100)->nullable()->after('payment_status');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_reference');
            $table->foreignId('payment_verified_by')
                  ->nullable()
                  ->after('payment_verified_at')
                  ->constrained('users')
                  ->nullOnDelete();

            // ── Admin notes (separate from customer message) ──────────────
            $table->text('admin_notes')->nullable()->after('preferred_contact_time');

            // ── Indexes ──────────────────────────────────────────────────
            $table->index('order_reference');
            $table->index('user_id');
            $table->index('motorcycle_id');
            $table->index('payment_status');
        });

        // Fix existing purchase enquiries that have enquiry_type='purchase'
        // (CartController was using this non-standard type)
        // We keep the value as 'purchase' since we're now adding it to the known types.
        // No data changes needed — the model/controller updates below will handle display.
    }

    public function down(): void
    {
        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropIndex(['order_reference']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['motorcycle_id']);
            $table->dropIndex(['payment_status']);

            $table->dropForeign(['user_id']);
            $table->dropForeign(['motorcycle_id']);
            $table->dropForeign(['payment_verified_by']);

            $table->dropColumn([
                'user_id', 'order_reference', 'motorcycle_id', 'motorcycle_snapshot',
                'unit_price', 'currency', 'quantity', 'total_amount', 'delivery_fee',
                'delivery_method', 'delivery_address', 'city', 'governorate',
                'payment_method', 'payment_status', 'payment_reference',
                'payment_verified_at', 'payment_verified_by', 'admin_notes',
            ]);
        });
    }
};
