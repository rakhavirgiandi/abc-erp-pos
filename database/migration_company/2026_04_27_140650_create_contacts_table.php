<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('province_id')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_phone')->nullable();
            $table->string('email')->nullable()->unique();
            $table->integer('is_pkp')->nullable();
            $table->integer('is_subcon')->nullable();
            $table->integer('is_pph_free')->nullable();
            $table->string('npwp')->nullable();
            $table->text('npwp_address')->nullable();
            $table->integer('is_staff')->nullable();
            $table->integer('is_customer')->nullable();
            $table->integer('is_supplier')->nullable();
            $table->integer('is_seller')->nullable();
            $table->integer('is_leads')->nullable();
            $table->integer('salesman_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('due_days')->nullable();
            $table->decimal('early_discount', 50, 2)->nullable();
            $table->decimal('late_fees', 50, 2)->nullable();
            $table->integer('is_active')->nullable();
            $table->string('salesman_name')->nullable();
            $table->integer('contact_group_id')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};