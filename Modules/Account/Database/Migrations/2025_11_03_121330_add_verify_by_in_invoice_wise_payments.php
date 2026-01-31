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
        Schema::table('invoice_wise_payments', function (Blueprint $table) {
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_wise_payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['verified_by', 'approved_by']);
        });
    }
};
