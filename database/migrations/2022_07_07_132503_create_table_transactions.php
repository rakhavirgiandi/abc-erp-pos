<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();;
            $table->uuid('company_id')->nullable();
            $table->string('number')->nullable();
            $table->string('date')->nullable();
            $table->decimal('discount_amount', 50, 4)->nullable();
            $table->string('discount_percentage', 5, 2)->nullable();
            $table->string('referral_code')->nullable();
            $table->string('voucher_code')->nullable();
            $table->decimal('total', 50, 4)->nullable();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
