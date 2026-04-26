<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('c_b_c_license_requisitions', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->after('product_model')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('c_b_c_license_requisitions', function (Blueprint $table) {
            $table->dropColumn('product_id');
        });
    }

};
