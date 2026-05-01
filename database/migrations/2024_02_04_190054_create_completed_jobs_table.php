<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompletedJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('start_at')->nullable();
            $table->dateTime('finish_at')->nullable();
            $table->longText('params')->nullable();
            $table->string('slug')->nullable();
            $table->string('job_name')->nullable();
            $table->decimal('percentages', 5, 2)->nullable();
            $table->timestamps();
        });

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->string('job_name')->nullable();
            $table->decimal('percentages', 5, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('completed_jobs');
    }
}
