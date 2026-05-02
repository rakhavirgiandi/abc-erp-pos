<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('pgsql_companies')->create('user_settings', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->integer('is_protected')->nullable();
            $table->integer('is_hidden')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('order')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('pgsql_companies')->dropIfExists('user_settings');
    }
};
