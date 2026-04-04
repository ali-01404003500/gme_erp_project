<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trigger_names', function (Blueprint $table) {
            $table->integer('after_send_time')->nullable()->after('status');  
        });
    }

    public function down(): void
    {
        Schema::table('trigger_names', function (Blueprint $table) {
            $table->dropColumn('after_send_time');
        });
    }
};
