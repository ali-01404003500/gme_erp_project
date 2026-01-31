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
        Schema::create('usg_or_opg_sms_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usg_or_opg_sms_id')->constrained('usg_or_opg_sms')->onDelete('cascade')->name('usg_or_opg_sms_id_foreign');
            $table->string('multiple_phone_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usg_or_opg_sms_phones');
    }
};
