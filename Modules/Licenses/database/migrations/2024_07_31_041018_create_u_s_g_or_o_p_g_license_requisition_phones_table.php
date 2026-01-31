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
        Schema::create('u_s_g_or_o_p_g_license_requisition_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('u_s_g_or_o_p_g_license_requisition_id')->constrained('u_s_g_or_o_p_g_license_requisitions')->onDelete('cascade')->name('license_requisition_id_foreign');
            $table->string('multiple_phone_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('u_s_g_or_o_p_g_license_requisition_phones');
    }
};
