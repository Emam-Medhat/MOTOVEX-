<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->decimal('engine_capacity', 8, 2)->nullable()->change();
            $table->integer('horsepower')->nullable()->change();
            $table->integer('torque')->nullable()->change();
            $table->decimal('price', 12, 2)->nullable()->change();
            $table->year('year')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->string('transmission')->nullable()->change();
            $table->string('currency', 3)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('motorcycles', function (Blueprint $table) {
            $table->decimal('engine_capacity', 8, 2)->nullable(false)->change();
            $table->integer('horsepower')->nullable(false)->change();
            $table->integer('torque')->nullable(false)->change();
            $table->decimal('price', 12, 2)->nullable(false)->change();
            $table->year('year')->nullable(false)->change();
            $table->string('category')->nullable(false)->change();
            $table->string('transmission')->nullable(false)->change();
            $table->string('currency', 3)->nullable(false)->change();
        });
    }
};
