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
        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('workflow_id');

            $table->unsignedBigInteger('reference_id');     // leave_application_id
            $table->string('reference_type');               // LeaveApplication model

            $table->integer('level');                       // approval step level
            $table->unsignedBigInteger('approver_id');

            $table->enum('status', ['pending','approved','rejected'])
                  ->default('pending');

            $table->timestamp('approved_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('workflow_id')
                  ->references('id')
                  ->on('approval_flows')
                  ->cascadeOnDelete();

            // optional: approver relation
            // $table->foreign('approver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_requests');
    }
};
