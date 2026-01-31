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
        Schema::table('advance_cheque_entries', function (Blueprint $table) {
            $table->longText('signature')->nullable();
            $table->string('signature_timestamp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advance_cheque_entries', function (Blueprint $table) {
            $table->dropColumn('signature');
            $table->dropColumn('signature_timestamp');
        });
    }
};
