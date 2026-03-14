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
        Schema::table('service_my_tasks', function (Blueprint $table) {

            $table->string('operator_info_training_status')
                  ->nullable()
                  ->after('handover_info_contact_no');

            $table->string('operator_info_name')
                  ->nullable()
                  ->after('operator_info_training_status');

            $table->string('operator_info_designation')
                  ->nullable()
                  ->after('operator_info_name');

            $table->string('operator_info_contact_no')
                  ->nullable()
                  ->after('operator_info_designation');

            $table->text('operator_comments')
                  ->nullable()
                  ->after('operator_info_contact_no');
        });
    }

    public function down(): void
    {
        Schema::table('service_my_tasks', function (Blueprint $table) {
            $table->dropColumn([
                'operator_info_training_status',
                'operator_info_name',
                'operator_info_designation',
                'operator_info_contact_no',
                'operator_comments',
            ]);
        });
    }
};
