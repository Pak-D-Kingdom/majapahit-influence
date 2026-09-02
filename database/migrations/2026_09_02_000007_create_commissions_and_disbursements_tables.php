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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('endorsement_id')->unique()->constrained('endorsements')->onDelete('cascade');
            $table->foreignId('kol_profile_id')->constrained('kol_profiles')->onDelete('cascade');
            $table->decimal('endorsement_fee', 15, 2)->default(0.00);
            $table->decimal('commission_pct', 5, 2)->default(0.00);
            $table->decimal('commission_amount', 15, 2)->default(0.00);
            $table->decimal('agency_amount', 15, 2)->default(0.00);
            $table->boolean('is_override')->default(false);
            $table->text('override_reason')->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected, dicairkan
            $table->date('disbursed_at')->nullable();
            $table->string('disbursement_proof_path', 500)->nullable();
            $table->timestamps();

            $table->index('kol_profile_id');
            $table->index('status');
        });

        Schema::create('commission_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('commission_id')->constrained('commissions')->onDelete('cascade');
            $table->string('action', 50); // request, approve, reject, disburse
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->string('proof_path', 500)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_approvals');
        Schema::dropIfExists('commissions');
    }
};
