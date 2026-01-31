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
        Schema::table('advance_cheque_entry_details', function (Blueprint $table) {
            $table->foreignId('emi_entry_details_id')->nullable()->constrained('e_m_i_entry_details')->after('advance_cheque_entry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_cheque_entry_details', function (Blueprint $table) {
            $table->dropForeign(['emi_entry_details_id']);
            $table->dropColumn('emi_entry_details_id');
        });
    }
};
