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
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->unsignedBigInteger('accepted_by')->nullable()->after('status');
            $table->timestamp('accepted_at')->nullable()->after('accepted_by');
            $table->json('accepted_data')->nullable()->after('accepted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropColumn(['accepted_by', 'accepted_at', 'accepted_data']);
        });
    }
};
