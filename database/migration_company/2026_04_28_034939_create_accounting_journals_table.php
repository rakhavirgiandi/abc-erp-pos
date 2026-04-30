<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_journals', function (Blueprint $table) {
            $table->id();

            $table->date('date')->nullable();
            $table->string('number')->nullable();
            $table->string('ref_number')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('currency_name')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('branch_name')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();
            $table->decimal('total', 50, 5)->nullable();
            $table->decimal('exchange_rate', 50, 5)->nullable();
            $table->string('status')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_journals');
    }
};
