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
        Schema::table('products', function (Blueprint $table) {
            $table->string('verification_status', 30)->default('pending')->change();
            $table->json('pending_changes')->nullable()->after('rejection_reason');
            $table->text('deletion_reason')->nullable()->after('pending_changes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['pending_changes', 'deletion_reason']);
        });
    }
};
