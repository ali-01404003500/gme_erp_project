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
        Schema::table('sales_requisitions', function (Blueprint $table) {
            $table->string('verify_remark')->nullable();
            $table->string('approve_remark')->nullable();
            $table->boolean('is_urgent_approval')->nullable();
            $table->foreignId('approve_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_requisitions', function (Blueprint $table) {
            //
            $table->dropColumn('verify_remark');
            $table->dropColumn('approve_remark');
            $table->dropColumn('is_urgent_approval');
            $table->dropForeign(['approve_by']);
            $table->dropColumn('approve_by');
        });
    }
};
