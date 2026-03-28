<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employement_details', function (Blueprint $table) {
            $table->date('date_of_termination')->nullable()->after('date_of_joining');
        });
    }

    public function down()
    {
        Schema::table('employement_details', function (Blueprint $table) {
            $table->dropColumn('date_of_termination');
        });
    }
};
