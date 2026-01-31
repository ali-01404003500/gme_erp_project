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
        Schema::create('service_pending_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_my_task_id')->constrained('service_my_tasks')->cascadeOnDelete();
            $table->foreignId('service_token_id')->constrained('service_tokens')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_pending_tokens');
    }
};
