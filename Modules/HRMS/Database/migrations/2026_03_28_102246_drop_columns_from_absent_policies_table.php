<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absent_policies', function (Blueprint $table) {
            $table->dropColumn(['deduct_from_salary', 'adjust_days']);
        });
    }

    public function down()
    {
        Schema::table('absent_policies', function (Blueprint $table) {
            $table->boolean('deduct_from_salary')->nullable();
            $table->integer('adjust_days')->nullable();
        });
    }
};
