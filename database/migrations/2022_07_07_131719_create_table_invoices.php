<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();;
            $table->string('number')->nullable();
            $table->uuid('transactions_id')->nullable();
            $table->decimal('total', 50, 4)->nullable();
            $table->string("callback_url")->nullable();
            $table->string("callback_status")->nullable();
            $table->string("callback_error")->nullable();
            $table->text('payment_data_req')->nullable();
            $table->text('payment_data_res')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
