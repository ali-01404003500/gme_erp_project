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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->default(1)->constrained('branches')->cascadeOnDelete();
            $table->foreignId("branch_type_id")->constrained('branch_types')->cascadeOnDelete();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('police_station')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('size')->nullable();
            $table->string('image')->nullable();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->foreignId("created_by")->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId("updated_by")->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
