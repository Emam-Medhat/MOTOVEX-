<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->after('brand_id')->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_manager_id')->nullable()->after('created_by')->constrained('users')->nullOnDelete();
            $table->decimal('rental_price_per_day', 10, 2)->nullable()->after('price');
            $table->index(['assigned_manager_id', 'created_by']);
        });

        Schema::table('enquiries', function (Blueprint $table) {
            $table->date('rental_start_date')->nullable()->after('motorcycle_id');
            $table->date('rental_end_date')->nullable()->after('rental_start_date');
            $table->unsignedSmallInteger('rental_days')->nullable()->after('rental_end_date');
            $table->decimal('rental_price_per_day', 10, 2)->nullable()->after('rental_days');
            $table->uuid('idempotency_key')->nullable()->after('order_reference');
            $table->index(['motorcycle_id', 'enquiry_type', 'status']);
            $table->unique(['idempotency_key', 'motorcycle_id']);
        });

        Schema::create('workflow_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id');
            $table->string('action');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamps();
            $table->index(['subject_type', 'subject_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workflow_activities');

        Schema::table('enquiries', function (Blueprint $table) {
            $table->dropUnique(['idempotency_key', 'motorcycle_id']);
            $table->dropIndex(['motorcycle_id', 'enquiry_type', 'status']);
            $table->dropColumn([
                'rental_start_date',
                'rental_end_date',
                'rental_days',
                'rental_price_per_day',
                'idempotency_key',
            ]);
        });

        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropIndex(['assigned_manager_id', 'created_by']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['assigned_manager_id']);
            $table->dropColumn(['created_by', 'assigned_manager_id', 'rental_price_per_day']);
        });
    }
};
