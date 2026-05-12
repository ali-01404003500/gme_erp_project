<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('daily_calls', function (Blueprint $table) {
            $table->dropColumn('sales_complain_details');
        });
    }

    public function down(): void
    {
        Schema::table('daily_calls', function (Blueprint $table) {
            $table->text('sales_complain_details')->nullable();
        });
    }
};
