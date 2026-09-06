<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_closings', function (Blueprint $table) {
            $table->id();

            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();
            $table->integer('product_category_id')->nullable();
            $table->text('description')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('branch_name')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('project_name')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_closings');
    }
};
