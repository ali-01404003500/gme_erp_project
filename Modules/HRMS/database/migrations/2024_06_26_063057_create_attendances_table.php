<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->string('remarks')->nullable();

            $table->date('check_in_date')->nullable();
            $table->time('check_in_time')->nullable();
            $table->string('check_in_latitude')->nullable();
            $table->string('check_in_longitude')->nullable();

            $table->date('check_out_date')->nullable();
            $table->time('check_out_time')->nullable();
            $table->string('check_out_latitude')->nullable();
            $table->string('check_out_longitude')->nullable();

            $table->string('status')->default(0);
            $table->string('attendance_type')->nullable();


            $table->softDeletes();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
