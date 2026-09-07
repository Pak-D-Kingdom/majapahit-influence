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
        Schema::table('commissions', function (Blueprint $table) {
            $table->index(['kol_profile_id', 'status'], 'commissions_kol_profile_id_status_index');
        });

        Schema::table('endorsements', function (Blueprint $table) {
            $table->index(['campaign_id', 'status'], 'endorsements_campaign_id_status_index');
            $table->index(['kol_profile_id', 'status'], 'endorsements_kol_profile_id_status_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['is_active', 'category_id'], 'products_is_active_category_id_index');
        });

        Schema::table('kol_profiles', function (Blueprint $table) {
            $table->index(['status', 'tier_id'], 'kol_profiles_status_tier_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropIndex('commissions_kol_profile_id_status_index');
        });

        Schema::table('endorsements', function (Blueprint $table) {
            $table->dropIndex('endorsements_campaign_id_status_index');
            $table->dropIndex('endorsements_kol_profile_id_status_index');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_is_active_category_id_index');
        });

        Schema::table('kol_profiles', function (Blueprint $table) {
            $table->dropIndex('kol_profiles_status_tier_id_index');
        });
    }
};
