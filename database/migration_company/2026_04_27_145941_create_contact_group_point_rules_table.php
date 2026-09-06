<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_group_point_rules', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->integer('contact_group_id')->nullable();
            $table->string('type')->nullable();
            $table->decimal('minimum_purchase', 50, 5)->nullable();
            $table->decimal('total_reward_point', 50, 5)->nullable();
            $table->decimal('qty', 50, 5)->nullable();
            $table->date('expired_date')->nullable();
            $table->smallInteger('is_applicable_multiple')->nullable();
            $table->smallInteger('is_active')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('product_category_id')->nullable();
            $table->integer('is_excluded_in_total_payment')->nullable();
            $table->integer('product_sku_id')->nullable();
            $table->integer('unit_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_group_point_rules');
    }
};
