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
        Schema::create('account_customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->decimal('opening_balance', 16, 2)->default(0);
            $table->decimal('current_balance', 16, 2)->default(0);
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            $table->decimal('previous_due')->default(0);
            
                
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_customers');
    }
};
