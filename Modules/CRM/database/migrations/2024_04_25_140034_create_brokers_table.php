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
        Schema::create('brokers', function (Blueprint $table) {
            $table->id();
            $table->string('broker_name');
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->string('alternative_phone')->nullable();
            $table->string('dob');
            $table->string('gender');
            $table->unsignedBigInteger('commission_type');
            $table->unsignedBigInteger('division_id');
            $table->unsignedBigInteger('district_id');
            $table->unsignedBigInteger('thana_id');
            $table->string('nid');
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('photograph')->nullable();
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('brokers');
    }
};
