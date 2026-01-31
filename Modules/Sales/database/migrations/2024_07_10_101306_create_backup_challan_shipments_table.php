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
        Schema::create('backup_challan_shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('backup_challan_id')->constrained('backup_challans')->onDelete('cascade');
            $table->unsignedBigInteger('courier_id')->nullable()->constrained('couriers')->onDelete('cascade');
            $table->unsignedBigInteger('area_id')->nullable()->constrained('areas')->onDelete('cascade');
            $table->string('address')->nullable();
            $table->string('contact_person_name')->nullable();
            $table->string('contact_person_number')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('backup_challan_shipments');
    }
};
