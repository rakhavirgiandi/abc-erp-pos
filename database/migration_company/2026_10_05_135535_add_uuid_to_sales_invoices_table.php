<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::connection('pgsql_companies')->hasColumn('sales_invoices', 'uuid')) {
            Schema::connection('pgsql_companies')->table('sales_invoices', function (Blueprint $table) {
                $table->uuid('uuid')->nullable();
            });
        }

        DB::connection('pgsql_companies')->statement(
            'CREATE UNIQUE INDEX IF NOT EXISTS sales_invoices_uuid_unique
            ON sales_invoices (uuid) WHERE uuid IS NOT NULL'
        );
    }

    public function down(): void
    {
        DB::connection('pgsql_companies')->statement(
            'DROP INDEX IF EXISTS sales_invoices_uuid_unique'
        );

        if (Schema::connection('pgsql_companies')->hasColumn('sales_invoices', 'uuid')) {
            Schema::connection('pgsql_companies')->table('sales_invoices', function (Blueprint $table) {
                $table->dropColumn('uuid');
            });
        }
    }
};