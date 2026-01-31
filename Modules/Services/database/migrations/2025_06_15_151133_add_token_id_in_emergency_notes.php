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
        Schema::table('emergency_notes', function (Blueprint $table) {
            $table->foreignId('service_token_id')
                ->nullable()
                ->constrained('service_tokens')
                ->cascadeOnDelete()
                ->after('id'); // Assuming you want to place it after the 'id' column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('emergency_notes', function (Blueprint $table) {
            $table->dropForeign(['service_token_id']);
            $table->dropColumn('service_token_id');
        });
    }
};
