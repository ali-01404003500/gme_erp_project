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
        Schema::create('cbc_sms_phones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cbc_sms_id')->constrained('cbc_sms')->onDelete('cascade')->name('cbc_sms_id_foreign');
            $table->string('multiple_phone_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cbc_sms_phones');
    }
};
