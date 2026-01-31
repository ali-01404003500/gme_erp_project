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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('location_type_id');
            $table->string('name');
            $table->text('address');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('map_link')->nullable();
            $table->string('map_iframe')->nullable();
            $table->string('contact_person_name');
            $table->string('contact_person_mobile');
            $table->string('contact_person_email');
            $table->string('operational_hours');
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
        Schema::dropIfExists('locations');
    }
};
