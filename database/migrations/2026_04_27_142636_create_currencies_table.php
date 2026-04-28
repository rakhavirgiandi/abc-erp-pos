<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();

            $table->string('code')->nullable();
            $table->string('symbol')->nullable();
            $table->string('name')->nullable();

            $table->string('default_receivable_coa')->nullable();
            $table->string('default_payable_coa')->nullable();
            $table->string('default_cash_coa')->nullable();
            $table->string('default_bank_coa')->nullable();

            $table->decimal('exchange_rate', 50, 5)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};