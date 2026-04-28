<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_invoice_details', function (Blueprint $table) {
            $table->id();

            $table->integer('sales_invoice_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('qty', 30, 5)->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->decimal('unit_price', 50, 5)->nullable();
            $table->text('note')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 50, 5)->nullable();
            $table->string('discount_coa')->nullable();
            $table->string('service_name')->nullable();
            $table->string('coa')->nullable();
            $table->integer('sales_delivery_id')->nullable();
            $table->string('sales_delivery_name')->nullable();
            $table->string('ref_number')->nullable();
            $table->decimal('discount_percentage', 50, 5)->nullable();
            $table->decimal('other_cost', 50, 5)->nullable()->default(0);
            $table->string('other_coa')->nullable();
            $table->decimal('tax_amount', 50, 5)->nullable()->default(0);
            $table->decimal('tax_percentage', 50, 5)->nullable()->default(0);
            $table->string('tax_coa')->nullable();
            $table->string('sales_delivery_number')->nullable();
            $table->decimal('other_income', 20, 5)->nullable();
            $table->string('other_income_coa')->nullable();
            $table->integer('tax_id')->nullable();
            $table->decimal('sales_return_qty', 50, 5)->nullable()->default(0);
            $table->decimal('base_qty', 50, 5)->nullable()->default(0);
            $table->decimal('base_unit_price', 50, 5)->nullable()->default(0);
            $table->integer('base_unit_id')->nullable();
            $table->smallInteger('is_product_unit_convert')->nullable()->default(0);
            $table->integer('sales_order_detail_id')->nullable()->default(0);
            $table->integer('product_sku_id')->nullable()->default(0);
            $table->integer('reward_point_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_invoice_details');
    }
};