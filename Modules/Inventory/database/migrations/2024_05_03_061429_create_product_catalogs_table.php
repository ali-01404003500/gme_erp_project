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
        Schema::create('product_catalogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_type_id')->constrained('product_types')->onDelete('cascade');
            $table->foreignId('product_brand_id')->nullable()->constrained('brands')->onDelete('cascade');
            $table->string('name', 255)->nullable(false);
            $table->string('model', 255)->nullable();
            $table->decimal('mrp', 10, 2)->nullable(false);
            $table->foreignId('unit_type_id')->constrained('units')->onDelete('cascade');
            $table->foreignId('product_tag_id')->nullable()->constrained('tags')->onDelete('cascade');
            $table->string('product_origin', 255)->nullable();
            $table->string('status', 255)->nullable();
            $table->string('is_serial', 255)->nullable();
            $table->string('is_expire_date', 255)->nullable();
            $table->string('is_warranty', 255)->nullable();
            $table->string('warranty_period', 255)->nullable();
            $table->integer('warranty_period_input')->nullable();
            $table->string('force_barcode_scan', 255)->nullable();
            $table->string('ecommerce_product', 255)->nullable();
            $table->text('description')->nullable();
            $table->json('image_uploads')->nullable();
            $table->string('catalog_file')->nullable();
            $table->string('price_list_file')->nullable();
            $table->string('profile_image_upload')->nullable();
            $table->timestamps();
            // created and updated by
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->softDeletes();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_catalogs');
    }
};
