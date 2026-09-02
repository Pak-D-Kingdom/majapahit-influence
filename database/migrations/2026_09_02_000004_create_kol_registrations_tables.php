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
        Schema::create('kol_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number', 20)->unique();
            $table->string('full_name', 255);
            $table->string('email', 255);
            $table->string('phone', 20);
            $table->string('city', 100)->nullable();
            $table->json('niches')->nullable();
            $table->json('social_media')->nullable();
            $table->text('expected_rate')->nullable();
            $table->text('join_reason')->nullable();
            $table->string('status', 20)->default('pending_review'); // pending_review, reviewed, approved, rejected
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('email');
        });

        Schema::create('registration_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('kol_registrations')->onDelete('cascade');
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
        Schema::dropIfExists('registration_files');
        Schema::dropIfExists('kol_registrations');
    }
};
