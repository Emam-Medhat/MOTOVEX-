<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->string('short_description', 300)->nullable()->after('slug');
            $table->string('transmission')->nullable()->after('torque');
            $table->string('currency', 3)->default('USD')->nullable()->after('price');
            $table->boolean('is_featured')->default(false)->after('is_published');
            $table->index('is_featured');
            $table->index('is_published');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->dropIndex(['is_featured']);
            $table->dropIndex(['is_published']);
            $table->dropIndex(['category']);
            $table->dropColumn([
                'short_description',
                'transmission',
                'currency',
                'is_featured',
            ]);
        });
    }
};
