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
        Schema::table('application_entries', function (Blueprint $table) {
            $table->foreignId('advance_cheque_entry_detail_id')->nullable()->constrained('advance_cheque_entry_details');
                        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_entries', function (Blueprint $table) {
            $table->dropForeign(['advance_cheque_entry_detail_id']);
            $table->dropColumn('advance_cheque_entry_detail_id');
        });
    }
};
