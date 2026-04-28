<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_holder_name')->nullable();
            $table->string('coa')->nullable();
            $table->text('notes')->nullable();
            $table->smallInteger('is_show_in_invoice')->default(0)->nullable();
            $table->integer('is_active')->nullable();
            $table->smallInteger('is_default_pos_payment')->nullable();
            $table->decimal('deduction', 20, 5)->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
