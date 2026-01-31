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
        Schema::table('make_payments', function (Blueprint $table) {
            $table->foreignId('verified_by')->nullable()->constrained('users')->after('status');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->foreignId('approved_by')->nullable()->constrained('users')->after('verified_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('make_payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['verified_by', 'verified_at', 'approved_by', 'approved_at']);
        });
    }
};
