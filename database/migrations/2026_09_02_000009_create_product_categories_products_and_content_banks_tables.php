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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 120)->unique();
            $table->string('icon', 50)->default('bi-tag');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name', 255);
            $table->string('slug', 280)->unique();
            $table->string('sku', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('price', 15, 2)->default(0.00);
            $table->decimal('locked_commission_percent', 5, 2)->default(40.00); // 40.00%
            $table->decimal('locked_commission_amount', 15, 2)->default(0.00);
            $table->string('image_path', 500)->nullable();
            $table->unsignedInteger('stock')->default(100);
            $table->enum('promotion_pathway', ['direct', 'marketplace', 'both'])->default('both');
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('brand_id');
            $table->index('category_id');
            $table->index('promotion_pathway');
            $table->index('is_active');
        });

        Schema::create('content_banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained('brands')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('products')->cascadeOnDelete();
            $table->string('title', 255);
            $table->enum('asset_type', ['image', 'video', 'copywriting', 'drive_link', 'document'])->default('image');
            $table->string('file_path', 500)->nullable();
            $table->string('external_url', 500)->nullable();
            $table->text('content_text')->nullable();
            $table->unsignedInteger('file_size')->default(0);
            $table->string('mime_type', 100)->nullable();
            $table->timestamps();

            $table->index('brand_id');
            $table->index('product_id');
            $table->index('asset_type');
        });

        Schema::create('brand_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name', 255);
            $table->string('company_name', 255)->nullable();
            $table->string('industry_category', 100);
            $table->string('pic_name', 255);
            $table->string('pic_title', 100)->nullable();
            $table->string('pic_email', 255);
            $table->string('pic_phone', 30);
            $table->string('social_media', 255)->nullable();
            $table->string('website', 255)->nullable();
            $table->enum('service_need', ['endorsement', 'maklon', 'both'])->default('both');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('service_need');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_registrations');
        Schema::dropIfExists('content_banks');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
    }
};
