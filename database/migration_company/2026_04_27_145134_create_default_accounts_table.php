<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('default_accounts', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->integer('is_protected')->nullable();
            $table->integer('is_hidden')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('default_accounts');
    }
};
