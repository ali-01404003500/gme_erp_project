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
        Schema::create('customer_setting_brokers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_setting_id')->constrained('customer_settings')->cascadeOnDelete();
            $table->foreignId('broker_id')->constrained('brokers')->cascadeOnDelete();
            $table->tinyInteger('broker_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_setting_brokers');
    }
};
