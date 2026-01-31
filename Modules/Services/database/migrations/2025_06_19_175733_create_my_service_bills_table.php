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

        Schema::create('my_service_bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_my_task_id')->constrained('service_my_tasks')->onDelete('cascade');
            $table->foreignId('product_id')->nullable()->constrained('product_catalogs')->onDelete('cascade');
            $table->integer('quantity')->nullable();
            $table->decimal('price')->nullable();
            $table->decimal('unit_discount')->nullable();
            $table->decimal('total_discount')->nullable();
            $table->decimal('amount')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('my_service_bills');
    }
};
