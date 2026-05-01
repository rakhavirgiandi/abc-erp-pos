<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCompanyCredentials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_credentials', function (Blueprint $table) {
            $table->uuid('id')->primary();;
            $table->uuid('company_id')->nullable();
            $table->string('db_driver')->nullable();
            $table->string('db_host')->nullable();
            $table->string('db_username')->nullable();
            $table->string('db_password')->nullable();
            $table->string('db_database')->nullable();
            $table->string('db_port')->nullable();
            $table->string('private_ip')->nullable();
            $table->string('public_ip')->nullable();
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
        Schema::dropIfExists('company_credentials');
    }
}
