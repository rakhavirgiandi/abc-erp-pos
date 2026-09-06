<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();

            $table->string('number')->nullable();
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->integer('is_from_sales_delivery')->nullable();
            $table->integer('sales_order_id')->nullable();
            $table->string('sales_order_name')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('salesman_id')->nullable();
            $table->integer('top_discount_days')->nullable();
            $table->integer('top_due_days')->nullable();
            $table->decimal('top_early_discount', 50, 5)->nullable();
            $table->decimal('top_late_charge', 50, 5)->nullable();
            $table->decimal('delivery_cost', 50, 5)->nullable();
            $table->string('delivery_coa')->nullable();
            $table->decimal('other_cost', 50, 5)->nullable();
            $table->string('other_coa')->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('discount_amount', 50, 5)->nullable();
            $table->string('discount_coa')->nullable();
            $table->string('status')->nullable();
            $table->decimal('down_payment_amount', 50, 5);
            $table->string('down_payment_coa')->nullable();
            $table->string('coa_cash')->nullable();
            $table->string('payment_type')->nullable();
            $table->decimal('total', 50, 5)->nullable();
            $table->integer('tax_id')->nullable();
            $table->decimal('tax_amount', 50, 5)->nullable();
            $table->decimal('tax_percentage', 5, 2)->nullable();
            $table->string('tax_name')->nullable();
            $table->string('tax_coa')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('branch_name')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('project_name')->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('currency_name')->nullable();
            $table->decimal('exchange_rate', 50, 5)->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->string('warehouse_name')->nullable();
            $table->decimal('subtotal', 50, 5)->nullable();
            $table->string('total_coa')->nullable();
            $table->integer('is_standard')->nullable();
            $table->integer('sales_quotation_id')->nullable();
            $table->string('sales_quotation_number')->nullable();
            $table->decimal('discount_percentage', 50, 5)->nullable();
            $table->decimal('other_income', 20, 5)->nullable();
            $table->string('other_income_coa')->nullable();
            $table->string('sales_return_status')->nullable()->default('pending');
            $table->integer('created_by')->nullable();
            $table->decimal('total_payment', 50, 5)->nullable();
            $table->decimal('total_change', 50, 5)->nullable();
            $table->integer('is_from_pos')->nullable()->default(0);
            $table->string('ref_number')->nullable();
            $table->integer('bank_account_id')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // CHECK constraint (PostgreSQL)
        DB::statement("
            ALTER TABLE sales_invoices
            ADD CONSTRAINT sales_invoices_sales_return_status_check
            CHECK (
                sales_return_status IN ('pending', 'partial', 'finish')
            )
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};