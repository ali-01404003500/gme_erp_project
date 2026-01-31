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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId("product_type_id" )->nullable()->constrained('product_types')->cascadeOnDelete();

            $table->string("mrp" )->nullable();
           
            $table->string("remainder_quantity" )->nullable();

            $table->string("description" )->nullable();

            //new columns

            $table->foreignId('product_catalog_id')->nullable()->constrained('product_catalogs')->cascadeOnDelete();
            // $table->text('description')->nullable();
            $table->string('type', 255)->nullable();
            $table->decimal('cost_price')->nullable();
            $table->integer('stock_quantity')->nullable();
            // $table->integer('remainder_quantity');
            // $table->decimal('mrp');
            $table->decimal('landed_price')->nullable();
            $table->decimal('transportation_cost')->nullable();
            $table->decimal('vat')->nullable();
            $table->decimal('tax')->nullable();
            $table->decimal('misc')->nullable();
            $table->decimal('total_price')->nullable();

            $table->integer('max_sales_qty')->nullable();
            $table->integer('total_sales_qty')->nullable();
            $table->string('applied_type', 100)->nullable();
            $table->string('inv_no')->nullable();
            $table->string('start_date')->nullable();
            $table->string('stop_date')->nullable();

            $table->unsignedInteger('max_purchase_qty')->nullable();
            $table->unsignedInteger('total_purchase_qty')->nullable();
            $table->unsignedInteger('last_purchase_price')->nullable();
            $table->unsignedInteger('stock_status')->nullable();
            $table->text('remarks')->nullable();
            $table->string('discount_type', 100)->nullable();
            $table->foreignId('product_tag_id')->nullable()->constrained('tags')->cascadeOnDelete();
            $table->string('hs_code')->nullable();
            $table->float('last_cost_price')->nullable();
            $table->string('product_status')->nullable();
            $table->float('max_sales_quantity')->nullable();
            $table->string("status")->nullable();
            $table->string("max_purchase_quantity")->nullable();
            $table->string("stock_info")->nullable();
            $table->decimal('min_discount')->default(0);
            $table->decimal('max_discount')->default(0);
            $table->decimal('dollar_price', 10, 5)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
