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
        Schema::create('c_b_c_license_requisition_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('c_b_c_license_requisition_id')->constrained('c_b_c_license_requisitions')->onDelete('cascade')->name('cbc_license_requisition_id_foreign');
            $table->string('multiple_phone_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('c_b_c_license_requisition_phones');
    }
};
