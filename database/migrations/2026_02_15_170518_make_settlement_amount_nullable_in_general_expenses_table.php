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
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->decimal('settlement_amount', 15, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('general_expenses', function (Blueprint $table) {
            $table->decimal('settlement_amount', 15, 2)->nullable(false)->change();
        });
    }
};
