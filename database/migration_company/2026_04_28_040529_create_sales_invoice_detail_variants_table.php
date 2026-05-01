<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_invoice_detail_variants', function (Blueprint $table) {
            $table->id();

            $table->integer('sales_invoice_detail_id');
            $table->integer('variant_id')->nullable()->default(0);
            $table->integer('option_id')->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_invoice_detail_variants');
    }
};