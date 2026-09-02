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
        Schema::table('dongle_or_serial_entries', function (Blueprint $table) {
            $table->json('file_upload')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('dongle_or_serial_entries', function (Blueprint $table) {
            $table->text('file_upload')->nullable()->change();
        });
    }
};
 