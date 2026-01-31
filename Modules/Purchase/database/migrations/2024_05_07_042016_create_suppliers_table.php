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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_place');
            $table->string('phone');
            $table->string('tnt_number')->nullable();   
            $table->string('email')->nullable();
            $table->string('contact_for_sms')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->string('country')->nullable();
            $table->decimal('opening_balance',14,2)->nullable();
            $table->text('address');
            $table->string('profile_picture')->nullable();
            $table->string('owner_name')->nullable();
            $table->string('owner_designation')->nullable();
            $table->string('owner_mobile')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_dob')->nullable();      
            $table->string('owner_address')->nullable();      
            $table->string('nid')->nullable();      
            $table->string('front_image')->nullable();      
            $table->string('back_image')->nullable();      
            $table->string('visiting_card_front')->nullable();  
            $table->string('visiting_card_back')->nullable();
            $table->string('trade_license')->nullable();
            $table->string('signature')->nullable();
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('country_code')->nullable();

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
        Schema::dropIfExists('suppliers');
    }
};
