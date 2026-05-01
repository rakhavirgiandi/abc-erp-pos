<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->string('purchase_coa')->nullable();
            $table->string('sales_coa')->nullable();
            $table->integer('is_active')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
