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
        Schema::table('service_tokens', function (Blueprint $table) {
            $table->string('internal_video_link')->nullable()->after('work_type');
            $table->string('external_video_link')->nullable()->after('internal_video_link');
            $table->string('documents')->nullable()->after('external_video_link');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_tokens', function (Blueprint $table) {
            $table->dropColumn('internal_video_link');
            $table->dropColumn('external_video_link');
            $table->dropColumn('documents');
        });
    }
};
