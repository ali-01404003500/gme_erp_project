<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("offerables", function (Blueprint $table) {
            $table->id();
            $table->foreignId("offer_id")->constrained("offers")->onDelete("cascade");
            $table->morphs("offerable"); // Creates offerable_type and offerable_id columns
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate relationships
            $table->unique(["offer_id", "offerable_type", "offerable_id"], "unique_offer_offerable");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("offerables");
    }
};

