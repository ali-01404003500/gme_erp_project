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
        Schema::table('general_notifications', function (Blueprint $table) {
            //
            $table->nullableMorphs('source');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_notifications', function (Blueprint $table) {
            //
            $table->dropColumn('source_type');
            $table->dropColumn('source_id');
        });
    }
};
