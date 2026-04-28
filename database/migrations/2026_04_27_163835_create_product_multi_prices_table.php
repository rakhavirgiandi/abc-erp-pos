<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_multi_prices', function (Blueprint $table) {
            $table->id();

            $table->integer('product_id')->nullable();
            $table->integer('contact_group_id')->nullable();
            $table->decimal('from_qty', 50, 5)->nullable();
            $table->decimal('to_qty', 50, 5)->nullable();
            $table->decimal('discount_percentage', 50, 5)->nullable();
            $table->decimal('discount_amount', 50, 5)->nullable();
            $table->string('discount_type')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->decimal('unit_price', 50, 5)->nullable();
            $table->integer('product_sku_id')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_multi_prices');
    }
};
