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
        //delete if table exists
        if (Schema::hasTable('service_my_tasks')) {
            Schema::dropIfExists('service_my_tasks');
        }


        //create table
        Schema::create('service_my_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_token_id')->constrained('service_tokens')->onDelete('cascade');
            $table->string('bill_type');
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->text('description')->nullable();
            $table->string('basic_info_supply_voltage')->nullable();
            $table->boolean('basic_info_generator_backup')->default(false);
            $table->string('basic_info_ground_voltage')->nullable();
            $table->string('basic_info_ups_backup')->default('no');
            $table->string('handover_info_name')->nullable();
            $table->string('handover_info_department')->nullable();
            $table->string('handover_info_designation')->nullable();
            $table->string('handover_info_contact_no')->nullable();
            $table->string('status')->default('pending');
            $table->json('attachments')->nullable();

            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_my_tasks');
    }
};
