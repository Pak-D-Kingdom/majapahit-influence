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
        Schema::create('kol_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('nickname', 100)->nullable();
            $table->text('bio')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->string('photo_path', 500)->nullable();
            $table->foreignId('tier_id')->nullable()->constrained('tiers')->nullOnDelete();
            $table->decimal('commission_override_pct', 5, 2)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account_number', 50)->nullable();
            $table->string('bank_account_name', 255)->nullable();
            $table->string('npwp', 30)->nullable();
            $table->string('status', 20)->default('pending'); // pending, aktif, nonaktif, blacklist
            $table->text('status_reason')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('status');
            $table->index('tier_id');
            $table->index('city');
        });

        Schema::create('kol_social_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kol_profile_id')->constrained('kol_profiles')->onDelete('cascade');
            $table->string('platform', 50); // instagram, tiktok, youtube, twitter, etc.
            $table->string('username', 255);
            $table->string('profile_url', 500)->nullable();
            $table->unsignedInteger('followers_count')->default(0);
            $table->decimal('engagement_rate', 5, 2)->default(0.00);
            $table->timestamps();

            $table->index(['kol_profile_id', 'platform']);
            $table->index('followers_count');
        });

        Schema::create('kol_rate_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kol_profile_id')->constrained('kol_profiles')->onDelete('cascade');
            $table->string('platform', 50);
            $table->string('content_type', 50); // feed_post, story, reels, video, etc.
            $table->decimal('rate', 15, 2)->default(0.00);
            $table->timestamps();

            $table->unique(['kol_profile_id', 'platform', 'content_type'], 'idx_rate_cards_kol_platform');
        });

        Schema::create('kol_niches', function (Blueprint $table) {
            $table->foreignId('kol_profile_id')->constrained('kol_profiles')->onDelete('cascade');
            $table->foreignId('niche_id')->constrained('niches')->onDelete('cascade');
            $table->primary(['kol_profile_id', 'niche_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kol_niches');
        Schema::dropIfExists('kol_rate_cards');
        Schema::dropIfExists('kol_social_media');
        Schema::dropIfExists('kol_profiles');
    }
};
