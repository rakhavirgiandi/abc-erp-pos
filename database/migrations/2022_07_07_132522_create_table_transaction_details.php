<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTransactionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();;
            $table->uuid('transaction_id')->nullable();
            $table->uuid('edition_id')->nullable();
            $table->uuid('period_id')->nullable();
            $table->string('product_name')->nullable();
            $table->string('qty')->nullable();
            $table->string('unit_price')->nullable();
            $table->decimal('discount_amount', 50, 4)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
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
        Schema::dropIfExists('transaction_details');
    }
}
