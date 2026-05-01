<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->integer('is_control_stock')->nullable();
            $table->string('inventory_coa')->nullable();
            $table->string('delivery_goods_coa')->nullable();
            $table->string('receipt_goods_coa')->nullable();
            $table->integer('is_purchase')->nullable();
            $table->string('cogs_coa')->nullable();
            $table->string('purchase_return_coa')->nullable();
            $table->integer('is_sales')->nullable();
            $table->string('sales_coa')->nullable();
            $table->string('sales_return_coa')->nullable();
            $table->integer('is_active')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};