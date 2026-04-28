<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_histories', function (Blueprint $table) {
            $table->id();
            $table->string('model')->nullable();
            $table->integer('model_id')->nullable();
            $table->integer('contact_id')->nullable();
            $table->decimal('point', 50, 5)->nullable();
            $table->text('note')->nullable();
            $table->timestamp('date')->nullable();
            $table->string('type')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_histories');
    }
};