<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->integer('product_category_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('unit_id')->nullable();
            $table->decimal('sale_price', 50, 5)->nullable();
            $table->decimal('purchase_price', 50, 5)->nullable();
            $table->text('description')->nullable();
            $table->decimal('sale_tax', 50, 5)->nullable();
            $table->decimal('purchase_tax', 50, 5)->nullable();
            $table->decimal('width', 20, 5)->nullable();
            $table->decimal('height', 20, 5)->nullable();
            $table->decimal('length', 20, 5)->nullable();
            $table->decimal('weight', 20, 5)->nullable();
            $table->integer('is_active')->nullable();
            $table->string('multi_price_type')->nullable();
            $table->string('brand')->nullable();
            $table->integer('product_base_id')->nullable();
            $table->integer('is_serial_number')->nullable();
            $table->integer('purchase_tax_id')->nullable();
            $table->integer('sale_tax_id')->nullable();
            $table->text('uid')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
