<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->string('collection_id')->nullable()->after('id');
        });
        
        // Update existing records to have collection_id - this will be handled separately
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->dropColumn('collection_id');
        });
    }
};