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
        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->string('name')->required();
            $table->string('branch')->nullable();
            $table->string('department')->nullable();
            $table->dateTime('start_date')->nullable()->comment('Holiday start date');
            $table->dateTime('end_date')->nullable()->comment('Holiday end date');
            $table->boolean('every_year')->nullable();

            $table->softDeletes();
            // $table->foreignId('created_by')->nullable()->constrained('users');
            // $table->foreignId('updated_by')->nullable()->constrained('users');
            // $table->foreignId('deleted_by')->nullable()->constrained('users');

             $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
             $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
             $table->foreignId('deleted_by')->nullable()->constrained('users')->nullOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
