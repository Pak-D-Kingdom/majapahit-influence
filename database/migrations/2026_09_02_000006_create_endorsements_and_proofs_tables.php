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
        Schema::create('endorsements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('kol_profile_id')->constrained('kol_profiles')->onDelete('cascade');
            $table->string('content_type', 50); // feed_post, story, reels, video, etc.
            $table->decimal('fee', 15, 2)->default(0.00);
            $table->date('deadline');
            $table->date('start_date')->nullable();
            $table->string('status', 30)->default('assigned'); // draft, assigned, in_progress, content_submitted, content_approved, content_rejected, selesai
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('kol_profile_id');
            $table->index('campaign_id');
            $table->index('status');
            $table->index('deadline');
        });

        Schema::create('content_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endorsement_id')->constrained('endorsements')->onDelete('cascade');
            $table->date('posted_at')->nullable();
            $table->string('post_url', 500)->nullable();
            $table->text('notes')->nullable();
            $table->string('review_status', 20)->default('pending'); // pending, approved, rejected
            $table->text('review_notes')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('content_proof_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('content_proof_id')->constrained('content_proofs')->onDelete('cascade');
            $table->string('file_path', 500);
            $table->string('file_name', 255);
            $table->unsignedInteger('file_size')->default(0);
            $table->string('mime_type', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_proof_files');
        Schema::dropIfExists('content_proofs');
        Schema::dropIfExists('endorsements');
    }
};
