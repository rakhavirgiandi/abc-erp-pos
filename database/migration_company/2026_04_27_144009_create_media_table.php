<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();

            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();
            $table->string('type')->nullable();
            $table->string('original_name')->nullable();
            $table->string('filepath')->nullable();
            $table->string('filename')->nullable();
            $table->string('filesize')->nullable();
            $table->string('mime')->nullable();
            $table->string('extension')->nullable();
            $table->string('url')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_response_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};