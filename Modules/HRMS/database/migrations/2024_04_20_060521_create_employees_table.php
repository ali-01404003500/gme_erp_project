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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('gender');
            $table->string('office_phone');
            $table->string('personal_mobile');
            $table->string('alternate_phone')->nullable();
            $table->string('email_address');
            $table->string('country');
            $table->string('city');
            $table->string('present_address');
            $table->string('permanent_address');
            $table->string('blood_group');
            $table->string('religion');
            $table->string('marital_status');
            $table->string('photograph')->nullable();
        
            $table->string('resume')->nullable();
            $table->string('national_id');
            $table->string('front_image')->nullable();
            $table->string('back_image')->nullable();
            $table->string('signature')->nullable();
            $table->string('address_proof')->nullable();
            $table->string('other_documents')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('routing_number')->nullable();
            $table->string('etin_number')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('date_of_birth')->nullable();
            $table->string('epf_number')->nullable();
           
            $table->string('email_accounts')->nullable();
            $table->string('software_access')->nullable();
            $table->string('additional_notes')->nullable();


            $table->tinyInteger('status')->default(1);
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
        Schema::dropIfExists('employees');
    }
};
