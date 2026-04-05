<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_info', function (Blueprint $table) {
            $table->id('id'); // Primary Key
            $table->string('sms_reference')->nullable();
            $table->dateTime('sms_send_time')->nullable();
            $table->unsignedBigInteger('sms_mem_id')->nullable();
            $table->string('sms_to'); // Receiver number
            $table->text('sms_text'); // Message body
            $table->string('sms_status')->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_info');
    }
};
