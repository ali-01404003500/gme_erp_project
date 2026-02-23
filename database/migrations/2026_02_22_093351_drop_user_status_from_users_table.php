<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

 return new class extends Migration
{
    public function up()
    {
        if (Schema::hasColumn('users', 'user_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('user_status');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_status')->nullable();
        });
    }
};