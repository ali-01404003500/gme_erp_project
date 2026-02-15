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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('personal_mobile')->nullable()->change();
            $table->text('present_address')->nullable()->change();
            $table->text('permanent_address')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('personal_mobile')->nullable(false)->change();
            $table->text('present_address')->nullable(false)->change();
            $table->text('permanent_address')->nullable(false)->change();
        });
    }
};
