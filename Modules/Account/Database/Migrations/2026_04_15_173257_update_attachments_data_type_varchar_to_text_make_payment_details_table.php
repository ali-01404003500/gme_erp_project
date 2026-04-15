<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('make_payment_details', function (Blueprint $table) {
            $table->text('attachments')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('make_payment_details', function (Blueprint $table) {
            $table->string('attachments', 255)->nullable()->change();
        });
    }
};
