<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{ 

    public function up(): void
    {
        Schema::table('u_s_g_or_o_p_g_license_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('product_model')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('u_s_g_or_o_p_g_license_requisitions', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }

};
