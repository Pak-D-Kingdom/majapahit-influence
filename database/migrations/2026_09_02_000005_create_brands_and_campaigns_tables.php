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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('industry', 100)->nullable();
            $table->text('address')->nullable();
            $table->string('logo_path', 500)->nullable();
            $table->string('pic_name', 255)->nullable();
            $table->string('pic_title', 100)->nullable();
            $table->string('pic_email', 255)->nullable();
            $table->string('pic_phone', 20)->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget', 15, 2)->default(0.00);
            $table->text('content_requirements')->nullable();
            $table->text('dos_and_donts')->nullable();
            $table->string('status', 20)->default('draft'); // draft, aktif, selesai
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            $table->index('brand_id');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });

        Schema::create('campaign_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
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
        Schema::dropIfExists('campaign_files');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('brands');
    }
};
