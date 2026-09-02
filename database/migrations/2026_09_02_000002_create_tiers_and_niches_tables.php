<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->unsignedInteger('min_followers')->default(0);
            $table->unsignedInteger('max_followers')->nullable();
            $table->decimal('commission_pct', 5, 2)->default(60.00);
            $table->decimal('agency_pct', 5, 2)->default(40.00);
            $table->timestamps();
        });

        Schema::create('niches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('niches');
        Schema::dropIfExists('tiers');
    }
};
