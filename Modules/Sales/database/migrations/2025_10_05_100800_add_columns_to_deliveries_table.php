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
        Schema::table('deliveries', function (Blueprint $table) {
            $table->foreignId('arranged_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->foreignId('checked_by')->nullable()->constrained('employees')->onDelete('set null');
            $table->string('carton_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropForeign(['arranged_by']);
            $table->dropColumn('arranged_by');
            $table->dropForeign(['checked_by']);
            $table->dropColumn('checked_by');
            $table->dropColumn('carton_no');
        });
    }
};