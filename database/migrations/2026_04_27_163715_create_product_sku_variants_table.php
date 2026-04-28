<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_sku_variants', function (Blueprint $table) {
            $table->id();

            $table->integer('product_sku_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->integer('option_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_sku_variants');
    }
};