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
        Schema::table('service_my_tasks', function (Blueprint $table) {
            $table->string('bill_description')->nullable();
            $table->string('return_bill_description')->nullable();
            $table->decimal('tips_amount', 10, 2)->nullable();
            $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_my_tasks', function (Blueprint $table) {
            $table->dropColumn('bill_description');
            $table->dropColumn('return_bill_description');
            $table->dropColumn('tips_amount');
            $table->dropColumn('remarks');
        });
    }
};
