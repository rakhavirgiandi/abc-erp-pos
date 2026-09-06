<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_points', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->decimal('total_point', 50, 5)->nullable();
            $table->string('benefit_type')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('qty')->nullable();
            $table->decimal('discount_percentage', 50, 5)->nullable();
            $table->decimal('discount_amount', 50, 5)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('maximum_discount_amount', 50, 5)->nullable();
            $table->smallInteger('is_active')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_points');
    }
};
