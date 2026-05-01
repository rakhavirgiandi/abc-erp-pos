<?php

namespace App\Jobs;

use App\Helpers\ModelHelper;
use App\Http\Controllers\API\Companies\v1\AccountingMasterController;
use App\Http\Controllers\API\Companies\v1\BankAccountController;
use App\Http\Controllers\API\Companies\v1\BaseUnitConversionController;
use App\Http\Controllers\API\Companies\v1\BranchController;
use App\Http\Controllers\API\Companies\v1\ContactController;
use App\Http\Controllers\API\Companies\v1\ContactGroupController;
use App\Http\Controllers\API\Companies\v1\ContactGroupPointRuleController;
use App\Http\Controllers\API\Companies\v1\CurrencyController;
use App\Http\Controllers\API\Companies\v1\DefaultAccountController;
use App\Http\Controllers\API\Companies\v1\GeneralSettingController;
use App\Http\Controllers\API\Companies\v1\MediumController;
use App\Http\Controllers\API\Companies\v1\ProductCategoryController;
use App\Http\Controllers\API\Companies\v1\ProductController;
use App\Http\Controllers\API\Companies\v1\ProductMultiPriceController;
use App\Http\Controllers\API\Companies\v1\ProductSkuController;
use App\Http\Controllers\API\Companies\v1\ProductSkuVariantController;
use App\Http\Controllers\API\Companies\v1\ProductUnitConversionController;
use App\Http\Controllers\API\Companies\v1\ProductVariantController;
use App\Http\Controllers\API\Companies\v1\RewardPointController;
use App\Http\Controllers\API\Companies\v1\RoleController;
use App\Http\Controllers\API\Companies\v1\SalesInvoiceController;
use App\Http\Controllers\API\Companies\v1\TaxController;
use App\Http\Controllers\API\Companies\v1\UnitController;
use App\Http\Controllers\API\Companies\v1\VariantController;
use App\Http\Controllers\API\Companies\v1\VariantOptionController;
use App\Http\Controllers\API\Companies\v1\WarehouseController;
use App\Models\CompanyCredentials;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $companyId;
    protected $userId;

    public function __construct($companyId, $userId)
    {
        $this->companyId = $companyId;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            Log::info('SYNC START', [
                'company_id' => $this->companyId
            ]);

            if ($this->userId) {
                Auth::loginUsingId($this->userId);
            }

            $credential = CompanyCredentials::where('company_id', $this->companyId)->first();

            if (!$credential) {
                throw new \Exception('Company credential not found');
            }

            config(['database.connections.pgsql_companies' => [
                'driver' => 'pgsql',
                'host' => $credential->db_host,
                'port' => $credential->db_port,
                'database' => $credential->db_database,
                'username' => $credential->db_username,
                'password' => $credential->db_password,
                'charset' => 'utf8',
                'prefix' => '',
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]]);

            DB::purge('pgsql_companies');
            DB::reconnect('pgsql_companies');

            $request = new \Illuminate\Http\Request();
            $request->headers->set('company-id', $this->companyId);

            app(RoleController::class)->syncToLocal($request);
            app(AccountingMasterController::class)->syncToLocal($request);
            app(BankAccountController::class)->syncToLocal($request);
            app(TaxController::class)->syncToLocal($request);
            app(UnitController::class)->syncToLocal($request);
            app(VariantController::class)->syncToLocal($request);
            app(VariantOptionController::class)->syncToLocal($request);
            app(WarehouseController::class)->syncToLocal($request);
            app(ProductCategoryController::class)->syncToLocal($request);
            app(BranchController::class)->syncToLocal($request);
            app(CurrencyController::class)->syncToLocal($request);
            app(BaseUnitConversionController::class)->syncToLocal($request);
            app(ContactController::class)->syncToLocal($request);
            app(ContactGroupController::class)->syncToLocal($request);
            app(ContactGroupPointRuleController::class)->syncToLocal($request);
            app(GeneralSettingController::class)->syncToLocal($request);
            app(DefaultAccountController::class)->syncToLocal($request);
            app(ProductController::class)->syncToLocal($request);
            app(MediumController::class)->syncToLocal($request);
            app(ProductMultiPriceController::class)->syncToLocal($request);
            app(ProductSkuController::class)->syncToLocal($request);
            app(ProductSkuVariantController::class)->syncToLocal($request);
            app(ProductUnitConversionController::class)->syncToLocal($request);
            app(ProductVariantController::class)->syncToLocal($request);
            app(RewardPointController::class)->syncToLocal($request);
            app(ProductController::class)->getStockDatatable($request);
            app(SalesInvoiceController::class)->syncToServer();

            ModelHelper::adjustSequencePostgreSql();
            Log::info('SYNC ALL FINISHED');

        } catch (\Exception $e) {
            Log::error('SYNC ALL ERROR: ' . $e->getMessage());
            throw $e;
        }
    }
}
