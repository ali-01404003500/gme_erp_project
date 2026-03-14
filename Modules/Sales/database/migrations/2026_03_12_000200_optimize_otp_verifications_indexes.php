<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds indexes to the otp_verifications table
     * to optimize the sales report queries and prevent memory allocation errors.
     */
    public function up(): void
    {
        // =====================================================
        // OTP_VERIFICATIONS TABLE INDEXES
        // =====================================================
        
        // Index for title filtering (used in sales report)
        if (!Schema::hasIndex('otp_verifications', 'otp_verifications_title_idx')) {
            Schema::table('otp_verifications', function (Blueprint $table) {
                $table->index('title', 'otp_verifications_title_idx');
            });
        }

        // Composite index for title + id (optimized for the sales report query)
        // This prevents filesort operations and memory allocation errors
        if (!Schema::hasIndex('otp_verifications', 'otp_verifications_title_id_idx')) {
            Schema::table('otp_verifications', function (Blueprint $table) {
                $table->index(['title', 'id'], 'otp_verifications_title_id_idx');
            });
        }

        // Index for sourceable_type and sourceable_id (polymorphic relationship)
        if (!Schema::hasIndex('otp_verifications', 'otp_verifications_sourceable_idx')) {
            Schema::table('otp_verifications', function (Blueprint $table) {
                $table->index(['sourceable_type', 'sourceable_id'], 'otp_verifications_sourceable_idx');
            });
        }

        // Composite index for title + sourceable relationship (most common query pattern)
        if (!Schema::hasIndex('otp_verifications', 'otp_verifications_title_sourceable_idx')) {
            Schema::table('otp_verifications', function (Blueprint $table) {
                $table->index(['title', 'sourceable_type', 'sourceable_id'], 'otp_verifications_title_sourceable_idx');
            });
        }

        // Index for created_at (if not already exists from original migration)
        if (!Schema::hasIndex('otp_verifications', 'otp_verifications_created_at_idx')) {
            Schema::table('otp_verifications', function (Blueprint $table) {
                $table->index('created_at', 'otp_verifications_created_at_idx');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropIndex('otp_verifications_title_idx');
            $table->dropIndex('otp_verifications_title_id_idx');
            $table->dropIndex('otp_verifications_sourceable_idx');
            $table->dropIndex('otp_verifications_title_sourceable_idx');
            $table->dropIndex('otp_verifications_created_at_idx');
        });
    }
};
