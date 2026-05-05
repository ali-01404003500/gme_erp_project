<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->string('company_seal')->nullable()->after('company_logo');
        });
    }

    public function down()
    {
        Schema::table('company_infos', function (Blueprint $table) {
            $table->dropColumn('company_seal');
        });
    }
};
