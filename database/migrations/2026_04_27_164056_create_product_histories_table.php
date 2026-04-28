<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_histories', function (Blueprint $table) {
            $table->id();

            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('ref_number')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('product_code')->nullable();
            $table->string('product_name')->nullable();
            $table->decimal('qty', 30, 5)->nullable();
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('branch_name')->nullable();
            $table->integer('unit_id')->nullable();
            $table->string('unit_name')->nullable();
            $table->decimal('unit_price', 50, 5)->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('currency_name')->nullable();
            $table->decimal('exchange_rate', 50, 5)->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->decimal('cogs_price', 50, 5)->nullable();
            $table->integer('product_sku_id')->nullable()->default(0);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_histories');
    }
};
