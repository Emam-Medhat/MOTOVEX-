<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('country', 100)->nullable()->after('phone');
            $table->string('city', 100)->nullable()->after('country');
            $table->foreignId('preferred_brand_id')->nullable()->after('city')
                ->constrained('brands')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('preferred_brand_id');
            $table->boolean('newsletter_opted_in')->default(false)->after('is_active');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropConstrainedForeignId('preferred_brand_id');
            $table->dropColumn([
                'phone',
                'country',
                'city',
                'is_active',
                'newsletter_opted_in',
            ]);
        });
    }
};
