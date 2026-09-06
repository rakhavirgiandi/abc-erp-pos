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
        Schema::table('companies', function (Blueprint $table) {
            $table->enum('business_type', ['contractor', 'developer'])->default('developer');
            $table->integer('main_project_quota')->default(0);
            $table->integer('main_lot_quota')->default(0);
            $table->tinyInteger('is_storefront')->default(0);
            $table->string('domain')->nullable();
            $table->string('subdomain')->nullable();
            $table->integer('storefront_project_quota')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('business_type');
            $table->dropColumn('main_project_quota');
            $table->dropColumn('main_lot_quota');
            $table->dropColumn('is_storefront');
            $table->dropColumn('domain');
            $table->dropColumn('subdomain');
            $table->dropColumn('storefront_project_quota');
        });
    }
};
