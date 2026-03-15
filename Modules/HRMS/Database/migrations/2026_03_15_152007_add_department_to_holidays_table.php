<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('holidays', function (Blueprint $table) {

            $table->unsignedBigInteger('department_id')
                ->nullable()
                ->after('name');
                
            $table->foreign('department_id')
                ->references('id')
                ->on('departments')
                ->cascadeOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('holidays', function (Blueprint $table) {

            $table->dropColumn('department_id');

        });
    }
};
