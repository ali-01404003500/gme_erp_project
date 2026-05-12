<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leave_applications', function (Blueprint $table) {

            $table->unsignedBigInteger('leave_year_id')
                ->nullable()
                ->after('id');

        });
    }

    public function down()
    {
        Schema::table('leave_applications', function (Blueprint $table) {

            $table->dropColumn('leave_year_id');

        });
    }
};
