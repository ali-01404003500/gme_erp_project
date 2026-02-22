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
        if (!Schema::hasColumn('loans', 'remarks')) {
            Schema::table('loans', function (Blueprint $table) {
                $table->text('remarks')
                      ->nullable()
                      ->after('monthly_reduction');
            });
        }
    }

    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn('remarks');
        });
    }
};
