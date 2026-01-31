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
            $table->string('status')->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('rejected_by')->nullable()->constrained('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_cheque_entry_details', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');
            $table->dropForeign(['rejected_by']);
            $table->dropColumn('rejected_by');
        });
    }
};
