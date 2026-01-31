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
        Schema::create('broker_commissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('broker_id');
            $table->unsignedBigInteger('commission_type');
            $table->unsignedBigInteger('percentage_type')->nullable();
            $table->unsignedBigInteger('fixed_type')->nullable();
            $table->double('fixed')->nullable();
            $table->double('percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broker_commissions');
    }
};
