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
        Schema::table('invoice_wise_collections', function (Blueprint $table) {
            //
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('verified_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_wise_collections', function (Blueprint $table) {
            $table->dropForeign(['approved_by','verified_by']);
            $table->dropColumn(['approved_by', 'verified_by']);
        });
    }
};
