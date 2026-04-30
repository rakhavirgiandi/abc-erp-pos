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
        Schema::create('base_unit_conversions', function (Blueprint $table) {
            $table->id();

            $table->integer('from_unit_id')->nullable();
            $table->integer('to_unit_id')->nullable();

            $table->decimal('from_value', 50, 5)->nullable();
            $table->decimal('to_value', 50, 5)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_unit_convertions');
    }
};
