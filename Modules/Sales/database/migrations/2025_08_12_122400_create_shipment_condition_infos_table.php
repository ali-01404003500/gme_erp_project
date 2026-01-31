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
        Schema::create('shipment_condition_infos', function (Blueprint $table) {

            $table->id();
            $table->morphs('for');

            $table->boolean('is_shipment')->default(1)->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->string('address')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_number')->nullable();
            $table->date('delivery_date')->nullable();
            $table->boolean('is_courier')->default(1)->nullable();
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->decimal('additional_amount', 20, 2)->nullable();
            $table->boolean('condition')->default(0)->nullable();
            $table->string('condition_remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipment_condition_infos');
    }
};
