<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salary_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Basic, House Rent etc
            $table->string('value');
            $table->boolean('status')->default(1); // 1=active
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('salary_breakdowns');
    }
};


