<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('test_ride_requests', function (Blueprint $table) {
            // Booking reference — unique human-readable code like TRB-20261029-00042
            $table->string('booking_reference', 30)->unique()->nullable()->after('id');

            // Link to authenticated user when logged in
            $table->foreignId('user_id')->nullable()->after('booking_reference')
                  ->constrained()->nullOnDelete();

            // Link to showroom (FK) alongside the free-text preferred_showroom kept for backwards compat
            $table->foreignId('showroom_id')->nullable()->after('motorcycle_id')
                  ->constrained()->nullOnDelete();

            // Date + time preferences
            $table->date('preferred_date')->nullable()->after('preferred_showroom');
            $table->string('preferred_time', 20)->nullable()->after('preferred_date');

            // Optional customer notes
            $table->text('notes')->nullable()->after('preferred_time');

            // Rename the status enum to cover new workflow states
            // existing values: new, contacted, scheduled, closed
            // new values: pending, confirmed, completed, cancelled
            // We keep the column but change the default and add an admin_notes field
            $table->text('admin_notes')->nullable()->after('notes');
        });

        // Update existing 'new' statuses to 'pending'
        \DB::table('test_ride_requests')
            ->where('status', 'new')
            ->update(['status' => 'pending']);

        // Generate booking references for existing records that don't have one
        $rows = \DB::table('test_ride_requests')->whereNull('booking_reference')->get();
        foreach ($rows as $row) {
            \DB::table('test_ride_requests')
                ->where('id', $row->id)
                ->update([
                    'booking_reference' => 'TRB-' . date('Ymd', strtotime($row->created_at)) . '-' . str_pad($row->id, 5, '0', STR_PAD_LEFT),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('test_ride_requests', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['showroom_id']);
            $table->dropColumn([
                'booking_reference',
                'user_id',
                'showroom_id',
                'preferred_date',
                'preferred_time',
                'notes',
                'admin_notes',
            ]);
        });
    }
};
