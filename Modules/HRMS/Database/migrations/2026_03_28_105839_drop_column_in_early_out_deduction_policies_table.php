<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('early_out_deduction_policies', function (Blueprint $table) {
            $table->dropColumn('consider_consecutive_early_out');
        });
    }

    public function down()
    {
        Schema::table('early_out_deduction_policies', function (Blueprint $table) {
            $table->boolean('consider_consecutive_early_out')->nullable();
        });
    }
};
