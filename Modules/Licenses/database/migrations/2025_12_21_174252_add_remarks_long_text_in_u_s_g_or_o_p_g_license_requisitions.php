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
        Schema::table('u_s_g_or_o_p_g_license_requisitions', function (Blueprint $table) {
                      $table->longText('remarks')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('u_s_g_or_o_p_g_license_requisitions', function (Blueprint $table) {
               $table->string('remarks')->nullable()->change();

        });
    }
};
