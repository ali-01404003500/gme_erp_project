<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leave_years', function (Blueprint $table) {
            $table->id();
            $table->string('year', 4)->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_closed')->default(0);
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('leave_years');
    }
};
