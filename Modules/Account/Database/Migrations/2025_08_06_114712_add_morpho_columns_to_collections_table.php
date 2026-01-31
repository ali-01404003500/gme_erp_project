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
        Schema::table('collections', function (Blueprint $table) {
            $table->dropForeign('collections_customer_id_foreign');
            $table->dropColumn('customer_id');
            $table->nullableMorphs('collection_from');
            $table->boolean('is_advance')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->dropMorphs('collection_from');
        });
    }
};
