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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account_number')->nullable();
            $table->unsignedBigInteger('account_group_id')->nullable();
            $table->unsignedBigInteger('account_control_id')->nullable();
            $table->unsignedBigInteger('account_subsidiary_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable()->default(1);
            $table->enum('balance_type', ['Debit', 'Credit'])->nullable();
            $table->decimal('opening_balance',  15)->default(0);
            $table->nullableMorphs('accountable');
            $table->text('remarks')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_deletable')->default(1);
            
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->foreign('account_group_id')->references('id')->on('account_groups');
            $table->foreign('account_control_id')->references('id')->on('account_controls');
            $table->foreign('account_subsidiary_id')->references('id')->on('account_subsidiaries');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
