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
        Schema::table('sales_commissions', function (Blueprint $table) {
            $table->foreignId('broker_bank_id')->nullable()->constrained('broker_banks')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_commissions', function (Blueprint $table) {
            $table->dropForeign(['broker_bank_id']);
            $table->dropColumn('broker_bank_id');
        });
    }
};
