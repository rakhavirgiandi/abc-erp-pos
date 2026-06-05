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
        Schema::table('products', function (Blueprint $table) {
            //
            Schema::connection('pgsql_companies')->table('products', function (Blueprint $table) {
                $table->renameColumn('product_base_id', 'product_catalog_id');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
            Schema::connection('pgsql_companies')->table('products', function (Blueprint $table) {
                $table->renameColumn('product_catalog_id', 'product_base_id');
            });
        });
    }
};
