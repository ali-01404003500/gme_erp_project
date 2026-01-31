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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('geo_locations');
            $table->foreignId('district_id')->constrained('geo_locations');
            $table->foreignId('thana_id')->constrained('geo_locations');
            $table->string('area');
            $table->foreignId('union_id')->nullable()->constrained('geo_locations');
            $table->foreignId('village_id')->nullable()->constrained('geo_locations');
            $table->string("post_code")->nullable();
            $table->string('street')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();

            $table->tinyInteger('status')->default(1);     
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
