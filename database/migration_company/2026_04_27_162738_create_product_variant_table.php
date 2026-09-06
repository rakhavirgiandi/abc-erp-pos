<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();

            $table->integer('product_id')->nullable();
            $table->integer('variant_id')->nullable();
            $table->integer('variant_option_id')->nullable();
            $table->integer('sequence')->nullable();
            $table->json('variant_option_ids')->nullable();
            
            $table->unique(
                ['product_id', 'variant_id', 'variant_option_id'],
                'product_variants_product_id_variant_id_variant_option_id_unique'
            );

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
