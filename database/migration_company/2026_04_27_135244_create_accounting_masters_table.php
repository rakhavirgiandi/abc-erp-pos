<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('accounting_masters', function (Blueprint $table) {
            $table->id();

            $table->string('coa')->nullable();
            $table->string('key')->nullable();
            $table->string('sub_coa')->nullable();
            $table->string('order_coa')->nullable();
            $table->string('accounting_code')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('is_active')->nullable();
            $table->integer('is_protected')->nullable();
            $table->string('cash_flow_type')->nullable();
            $table->integer('is_cash_bank')->nullable();
            $table->smallInteger('is_hidden')->default(0)->nullable();
            $table->string('ratio_type')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounting_masters');
    }
};
