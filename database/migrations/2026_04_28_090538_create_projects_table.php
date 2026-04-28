<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->date('order_date')->nullable();
            $table->date('delivery_date')->nullable();

            $table->integer('manager_project_id')->nullable();
            $table->integer('customer_id')->nullable();

            $table->string('status')->nullable();

            $table->decimal('percentage_done', 5, 2)->nullable();

            $table->string('order_number')->nullable();

            $table->decimal('estimated_cost', 50, 5)->nullable();
            $table->decimal('budget_amount', 50, 5)->nullable();

            $table->text('description')->nullable();

            $table->integer('is_active')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
