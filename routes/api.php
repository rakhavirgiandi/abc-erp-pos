<?php
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\UserCompanyController;
use App\Http\Controllers\API\UserController as CentralUserController;
use App\Http\Controllers\API\Companies\v1\MiscellaneousController;
use App\Http\Controllers\API\Companies\v1\AccountingJournalController;
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
use App\Http\Controllers\API\Companies\v1\ModelHasPermissionController;
use App\Http\Controllers\API\Companies\v1\ModelHasRoleController;
use App\Http\Controllers\API\Companies\v1\PermissionController;
use App\Http\Controllers\API\Companies\v1\PointHistoryController;
use App\Http\Controllers\API\Companies\v1\PointOfSalesController;
use App\Http\Controllers\API\Companies\v1\ProductCategoryController;
use App\Http\Controllers\API\Companies\v1\ProductClosingController;
use App\Http\Controllers\API\Companies\v1\ProductController;
use App\Http\Controllers\API\Companies\v1\ProductHistoryController;
use App\Http\Controllers\API\Companies\v1\ProductMultiPriceController;
use App\Http\Controllers\API\Companies\v1\ProductSkuController;
use App\Http\Controllers\API\Companies\v1\ProductSkuVariantController;
use App\Http\Controllers\API\Companies\v1\ProductUnitConversionController;
use App\Http\Controllers\API\Companies\v1\ProductVariantController;
use App\Http\Controllers\API\Companies\v1\RegRegencyController;
use App\Http\Controllers\API\Companies\v1\RewardPointController;
use App\Http\Controllers\API\Companies\v1\RoleController;
use App\Http\Controllers\API\Companies\v1\RoleHasPermissionController;
use App\Http\Controllers\API\Companies\v1\SalesInvoiceController;
use App\Http\Controllers\API\Companies\v1\SalesInvoiceDetailController;
use App\Http\Controllers\API\Companies\v1\SalesInvoiceDetailVariantController;
use App\Http\Controllers\API\Companies\v1\TaxController;
use App\Http\Controllers\API\Companies\v1\UnitController;
use App\Http\Controllers\API\Companies\v1\UserController;
use App\Http\Controllers\API\Companies\v1\VariantController;
use App\Http\Controllers\API\Companies\v1\VariantOptionController;
use App\Http\Controllers\API\Companies\v1\WarehouseController;
use App\Http\Controllers\API\CompanyController;
use App\Http\Controllers\API\SubscriptionController;
use App\Models\Companies;
use App\Models\Users;
use App\Models\CompanyCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// ---- Route Use Generator ----

Route::middleware('auth:api')->get('/me', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/v1/user_companies/me', function (Request $request) {
    $headers = $request->header();

    $company_id = isset($headers['company-id']) ? $headers['company-id'][0] : null;

    if ($company_id) {
        $company_credential = CompanyCredentials::where('company_id', $company_id)->first();

        if ($company_credential) {
            config(['database.connections.pgsql_companies' => [
                'driver' => 'pgsql',
                'host' => $company_credential['db_host'],
                'port' => $company_credential['db_port'],
                'database' => $company_credential['db_database'],
                'username' => $company_credential['db_username'],
                'password' => $company_credential['db_password'],
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]]);

            $company = Companies::where('id', $company_id)->first();

            $user_central = $request->user();

            $user = Users::select('users.*', 'roles.name as role_name')->where('email', $user_central->email)->join('roles', 'roles.id', '=', 'users.role_id')->withTrashed()->first();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email tidak terdaftar pada '.$company['name'],
                    'data' => null
                ], 400);
            }

            if ($user['deleted_at']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email anda telah dihapus oleh '.$company['name'].'. Harap kontak admin.',
                    'data' => null
                ], 400);
            }

            return $user;
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Perusahaan sudah di hapus dari MyMandep, harap kontak admin perumahan Anda',
                'data' => null
            ], 400);
        }
    }
});

Route::post('login', [CentralUserController::class, 'login']);
Route::post('duitku_callback', [InvoiceController::class, 'duitkuCallback']);
Route::post('forgot_passwords', [CentralUserController::class, 'forgotPassword']);
Route::post('change_passwords', [CentralUserController::class, 'forgotPassword']);
Route::post('new_passwords/{code}', [CentralUserController::class, 'newPassword']);
Route::post('user_delete_requests', [CentralUserController::class, 'postUserDeleteRequest'])->name('post.user_delete_requests');

Route::controller(RegProvinceController::class)->group(function() {
    Route::get('reg_provinces/{id?}', 'get')->name('get.reg_provinces');
    Route::post('reg_provinces', 'post')->name('post.reg_provinces');
    Route::patch('reg_provinces/{id}', 'patch')->name('patch.reg_provinces');
    Route::put('reg_provinces/{id}', 'put')->name('put.reg_provinces');
    Route::delete('reg_provinces/{id}', 'delete')->name('delete.reg_provinces');
    Route::post('reg_provinces_datatables', 'datatables')->name('datatable.reg_provinces');
});

Route::controller(RegRegencyController::class)->group(function() {
    Route::get('reg_regencies/{id?}', 'get')->name('get.reg_regencies');
    Route::post('reg_regencies', 'post')->name('post.reg_regencies');
    Route::patch('reg_regencies/{id}', 'patch')->name('patch.reg_regencies');
    Route::put('reg_regencies/{id}', 'put')->name('put.reg_regencies');
    Route::delete('reg_regencies/{id}', 'delete')->name('delete.reg_regencies');
    Route::post('reg_regencies_datatables', 'datatables')->name('datatable.reg_regencies');
});

Route::controller(RegDistrictController::class)->group(function() {
    Route::get('reg_districts/{id?}', 'get')->name('get.reg_districts');
    Route::post('reg_districts', 'post')->name('post.reg_districts');
    Route::patch('reg_districts/{id}', 'patch')->name('patch.reg_districts');
    Route::put('reg_districts/{id}', 'put')->name('put.reg_districts');
    Route::delete('reg_districts/{id}', 'delete')->name('delete.reg_districts');
    Route::post('reg_districts_datatables', 'datatables')->name('datatable.reg_districts');
});

Route::controller(RegVillageController::class)->group(function() {
    Route::get('reg_villages/{id?}', 'get')->name('get.reg_villages');
    Route::post('reg_villages', 'post')->name('post.reg_villages');
    Route::patch('reg_villages/{id}', 'patch')->name('patch.reg_villages');
    Route::put('reg_villages/{id}', 'put')->name('put.reg_villages');
    Route::delete('reg_villages/{id}', 'delete')->name('delete.reg_villages');
    Route::post('reg_villages_datatables', 'datatables')->name('datatable.reg_villages');
});

Route::controller(AssociationController::class)->group(function() {
    Route::get('associations/{id?}', 'get')->name('get.associations');
    Route::post('associations', 'post')->name('post.associations');
    Route::patch('associations/{id}', 'patch')->name('patch.associations');
    Route::put('associations/{id}', 'put')->name('put.associations');
    Route::delete('associations/{id}', 'delete')->name('delete.associations');
    Route::post('associations_datatables', 'datatables')->name('datatable.associations');
});

Route::controller(AdminController::class)->group(function() {
    Route::get('admins/{id?}', 'get')->name('get.admins');
    Route::post('admins', 'post')->name('post.admins');
    Route::patch('admins/{id}', 'patch')->name('patch.admins');
    Route::put('admins/{id}', 'put')->name('put.admins');
    Route::delete('admins/{id}', 'delete')->name('delete.admins');
    Route::post('admins_datatables', 'datatables')->name('datatable.admins');
});

Route::controller(CentralRoleController::class)->group(function() {
    Route::get('roles/{id?}', 'get')->name('get.roles');
    Route::post('roles', 'post')->name('post.roles');
    Route::patch('roles/{id}', 'patch')->name('patch.roles');
    Route::put('roles/{id}', 'put')->name('put.roles');
    Route::delete('roles/{id}', 'delete')->name('delete.roles');
    Route::post('roles_datatables', 'datatables')->name('datatable.roles');
});

Route::controller(CentralModelHasRoleController::class)->group(function() {
    Route::get('model_has_roles/{id?}', 'get')->name('get.model_has_roles');
    Route::post('model_has_roles', 'post')->name('post.model_has_roles');
    Route::patch('model_has_roles/{id}', 'patch')->name('patch.model_has_roles');
    Route::put('model_has_roles/{id}', 'put')->name('put.model_has_roles');
    Route::delete('model_has_roles/{id}', 'delete')->name('delete.model_has_roles');
    Route::post('model_has_roles_datatables', 'datatables')->name('datatable.model_has_roles');
});

Route::controller(CentralPermissionController::class)->group(function() {
    Route::get('permissions/{id?}', 'get')->name('get.permissions');
    Route::post('permissions', 'post')->name('post.permissions');
    Route::patch('permissions/{id}', 'patch')->name('patch.permissions');
    Route::put('permissions/{id}', 'put')->name('put.permissions');
    Route::delete('permissions/{id}', 'delete')->name('delete.permissions');
    Route::post('permissions_datatables', 'datatables')->name('datatable.permissions');
});

Route::controller(CentralModelHasPermissionController::class)->group(function() {
    Route::get('model_has_permissions/{id?}', 'get')->name('get.model_has_permissions');
    Route::post('model_has_permissions', 'post')->name('post.model_has_permissions');
    Route::patch('model_has_permissions/{id}', 'patch')->name('patch.model_has_permissions');
    Route::put('model_has_permissions/{id}', 'put')->name('put.model_has_permissions');
    Route::delete('model_has_permissions/{id}', 'delete')->name('delete.model_has_permissions');
    Route::post('model_has_permissions_datatables', 'datatables')->name('datatable.model_has_permissions');
});

Route::controller(CentralRoleHasPermissionController::class)->group(function() {
    Route::get('role_has_permissions/{id?}', 'get')->name('get.role_has_permissions');
    Route::post('role_has_permissions', 'post')->name('post.role_has_permissions');
    Route::patch('role_has_permissions/{id}', 'patch')->name('patch.role_has_permissions');
    Route::put('role_has_permissions/{id}', 'put')->name('put.role_has_permissions');
    Route::delete('role_has_permissions/{id}', 'delete')->name('delete.role_has_permissions');
    Route::post('role_has_permissions_datatables', 'datatables')->name('datatable.role_has_permissions');
});

Route::controller(PasswordResetTokenController::class)->group(function() {
    Route::get('password_reset_tokens/{id?}', 'get')->name('get.password_reset_tokens');
    Route::post('password_reset_tokens', 'post')->name('post.password_reset_tokens');
    Route::patch('password_reset_tokens/{id}', 'patch')->name('patch.password_reset_tokens');
    Route::put('password_reset_tokens/{id}', 'put')->name('put.password_reset_tokens');
    Route::delete('password_reset_tokens/{id}', 'delete')->name('delete.password_reset_tokens');
    Route::post('password_reset_tokens_datatables', 'datatables')->name('datatable.password_reset_tokens');
});

Route::controller(JobController::class)->group(function() {
    Route::get('jobs/{id?}', 'get')->name('get.jobs');
    Route::post('jobs', 'post')->name('post.jobs');
    Route::patch('jobs/{id}', 'patch')->name('patch.jobs');
    Route::put('jobs/{id}', 'put')->name('put.jobs');
    Route::delete('jobs/{id}', 'delete')->name('delete.jobs');
    Route::post('jobs_datatables', 'datatables')->name('datatable.jobs');
});

Route::controller(CompletedJobController::class)->group(function() {
    Route::get('completed_jobs/{id?}', 'get')->name('get.completed_jobs');
    Route::post('completed_jobs', 'post')->name('post.completed_jobs');
    Route::patch('completed_jobs/{id}', 'patch')->name('patch.completed_jobs');
    Route::put('completed_jobs/{id}', 'put')->name('put.completed_jobs');
    Route::delete('completed_jobs/{id}', 'delete')->name('delete.completed_jobs');
    Route::post('completed_jobs_datatables', 'datatables')->name('datatable.completed_jobs');
});

Route::middleware(['auth:api'])->group(function () {
    if (env('IS_ONPREMISE', false)) {
        config([
            'default_db_host' => env('DEFAULT_DB_HOST', '127.0.0.1'),
            'default_db_port' => env('DEFAULT_DB_PORT', '5432'),
            'default_db_driver' => env('DEFAULT_DB_DRIVER', 'pgsql'),
            'default_db_user' => env('DEFAULT_DB_USERNAME', 'root'),
            'default_db_password' => env('DEFAULT_DB_PASSWORD', ''),
        ]);

        config([
            'server_url' => env('SERVER_URL', 'https://app.abcerp.id'),
            'server_email'    => env('SERVER_EMAIL', 'admin@gmail.com'),
            'server_password' => env('SERVER_PASSWORD', '123'),
        ]);
    }

    Route::controller(CentralUserController::class)->group(function() {
        Route::get('users/{id?}', 'get')->name('get.users');
        Route::post('users', 'post')->name('post.users');
        Route::patch('users/{id}', 'patch')->name('patch.users');
        Route::put('users/{id}', 'put')->name('put.users');
        Route::delete('users/{id}', 'delete')->name('delete.users');
        Route::post('users_datatables', 'datatables')->name('datatable.users');
    });
    Route::controller(CompanyController::class)->group(function() {
        Route::post('companies/{id}/activates', 'postActivateCompany')->name('get.companies.activities');

        Route::get('companies/{id?}', 'get')->name('get.companies');
        Route::middleware(['setup.config'])->group(function () {
            Route::post('companies', 'post')->name('post.companies');
            Route::delete('companies/{id}', 'delete')->name('delete.companies');
        });
        Route::patch('companies/{id}', 'patch')->name('patch.companies');
        Route::put('companies/{id}', 'put')->name('put.companies');
        Route::post('companies_datatables', 'datatables')->name('datatable.companies');
    });
    Route::controller(SubscriptionController::class)->group(function() {
        Route::get('subscriptions/{id?}', 'get')->name('get.subscriptions');
        Route::post('subscriptions', 'post')->name('post.subscriptions');
        Route::patch('subscriptions/{id}', 'patch')->name('patch.subscriptions');
        Route::put('subscriptions/{id}', 'put')->name('put.subscriptions');
        Route::delete('subscriptions/{id}', 'delete')->name('delete.subscriptions');
        Route::post('subscriptions_datatables', 'datatables')->name('datatable.subscriptions');
    });
    Route::controller(InvoiceController::class)->group(function() {
        Route::get('invoices/{id?}', 'get')->name('get.invoices');
        Route::post('invoices', 'post')->name('post.invoices');
        Route::patch('invoices/{id}', 'patch')->name('patch.invoices');
        Route::put('invoices/{id}', 'put')->name('put.invoices');
        Route::delete('invoices/{id}', 'delete')->name('delete.invoices');
        Route::post('invoices_datatables', 'datatables')->name('datatable.invoices');
    });
    Route::controller(TransactionController::class)->group(function() {
        Route::get('transactions/{id?}', 'get')->name('get.transactions');
        Route::post('transactions', 'post')->name('post.transactions');
        Route::patch('transactions/{id}', 'patch')->name('patch.transactions');
        Route::put('transactions/{id}', 'put')->name('put.transactions');
        Route::delete('transactions/{id}', 'delete')->name('delete.transactions');
        Route::post('transactions_datatables', 'datatables')->name('datatable.transactions');
    });
    Route::controller(TransactionDetailController::class)->group(function() {
        Route::get('transaction_details/{id?}', 'get')->name('get.transaction_details');
        Route::post('transaction_details', 'post')->name('post.transaction_details');
        Route::patch('transaction_details/{id}', 'patch')->name('patch.transaction_details');
        Route::put('transaction_details/{id}', 'put')->name('put.transaction_details');
        Route::delete('transaction_details/{id}', 'delete')->name('delete.transaction_details');
        Route::post('transaction_details_datatables', 'datatables')->name('datatable.transaction_details');
    });
    Route::controller(EditionController::class)->group(function() {
        Route::get('editions/{id?}', 'get')->name('get.editions');
        Route::post('editions', 'post')->name('post.editions');
        Route::patch('editions/{id}', 'patch')->name('patch.editions');
        Route::put('editions/{id}', 'put')->name('put.editions');
        Route::delete('editions/{id}', 'delete')->name('delete.editions');
        Route::post('editions_datatables', 'datatables')->name('datatable.editions');
    });
    Route::controller(PeriodController::class)->group(function() {
        Route::get('periods/{id?}', 'get')->name('get.periods');
        Route::post('periods', 'post')->name('post.periods');
        Route::patch('periods/{id}', 'patch')->name('patch.periods');
        Route::put('periods/{id}', 'put')->name('put.periods');
        Route::delete('periods/{id}', 'delete')->name('delete.periods');
        Route::post('periods_datatables', 'datatables')->name('datatable.periods');
    });
    Route::controller(ReferralCodeController::class)->group(function() {
        Route::get('referral_codes/{id?}', 'get')->name('get.referral_codes');
        Route::post('referral_codes', 'post')->name('post.referral_codes');
        Route::patch('referral_codes/{id}', 'patch')->name('patch.referral_codes');
        Route::put('referral_codes/{id}', 'put')->name('put.referral_codes');
        Route::delete('referral_codes/{id}', 'delete')->name('delete.referral_codes');
        Route::post('referral_codes_datatables', 'datatables')->name('datatable.referral_codes');
    });
    Route::controller(VoucherController::class)->group(function() {
        Route::get('vouchers/{id?}', 'get')->name('get.vouchers');
        Route::post('vouchers', 'post')->name('post.vouchers');
        Route::patch('vouchers/{id}', 'patch')->name('patch.vouchers');
        Route::put('vouchers/{id}', 'put')->name('put.vouchers');
        Route::delete('vouchers/{id}', 'delete')->name('delete.vouchers');
        Route::post('vouchers_datatables', 'datatables')->name('datatable.vouchers');
    });
    Route::controller(EmailQueueController::class)->group(function() {
        Route::get('email_queues/{id?}', 'get')->name('get.email_queues');
        Route::post('email_queues', 'post')->name('post.email_queues');
        Route::patch('email_queues/{id}', 'patch')->name('patch.email_queues');
        Route::put('email_queues/{id}', 'put')->name('put.email_queues');
        Route::delete('email_queues/{id}', 'delete')->name('delete.email_queues');
        Route::post('email_queues_datatables', 'datatables')->name('datatable.email_queues');
    });
    Route::controller(CentralGeneralSettingController::class)->group(function() {
        Route::get('general_settings/{id?}', 'get')->name('get.general_settings');
        Route::post('general_settings', 'post')->name('post.general_settings');
        Route::patch('general_settings/{id}', 'patch')->name('patch.general_settings');
        Route::put('general_settings/{id}', 'put')->name('put.general_settings');
        Route::delete('general_settings/{id}', 'delete')->name('delete.general_settings');
        Route::post('general_settings_datatables', 'datatables')->name('datatable.general_settings');
    });
    Route::controller(SubscriptionHistoryController::class)->group(function() {
        Route::get('subscription_histories/{id?}', 'get')->name('get.subscription_histories');
        Route::post('subscription_histories', 'post')->name('post.subscription_histories');
        Route::patch('subscription_histories/{id}', 'patch')->name('patch.subscription_histories');
        Route::put('subscription_histories/{id}', 'put')->name('put.subscription_histories');
        Route::delete('subscription_histories/{id}', 'delete')->name('delete.subscription_histories');
        Route::post('subscription_histories_datatables', 'datatables')->name('datatable.subscription_histories');
    });
    Route::controller(CompanyCredentialController::class)->group(function() {
        Route::get('company_credentials/{id?}', 'get')->name('get.company_credentials');
        Route::post('company_credentials', 'post')->name('post.company_credentials');
        Route::patch('company_credentials/{id}', 'patch')->name('patch.company_credentials');
        Route::put('company_credentials/{id}', 'put')->name('put.company_credentials');
        Route::delete('company_credentials/{id}', 'delete')->name('delete.company_credentials');
        Route::post('company_credentials_datatables', 'datatables')->name('datatable.company_credentials');
    });
    Route::controller(UserCompanyController::class)->group(function() {
        Route::get('user_companies/{id?}', 'get')->name('get.user_companies');
        Route::post('user_companies', 'post')->name('post.user_companies');
        Route::patch('user_companies/{id}', 'patch')->name('patch.user_companies');
        Route::put('user_companies/{id}', 'put')->name('put.user_companies');
        Route::delete('user_companies/{id}', 'delete')->name('delete.user_companies');
        Route::post('user_companies_datatables', 'datatables')->name('datatable.user_companies');
    });
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:api', 'api.companies']], function() {
    Route::get('migrations', [MiscellaneousController::class, 'migration'])->name('get.migration');
    Route::get('user_settings', [MiscellaneousController::class, 'userSetting'])->name('get.user_settings');

    Route::controller(UserController::class)->group(function() {
        Route::get('users/{id?}', 'get')->name('v1.get.users');
        Route::post('users', 'post')->name('v1.post.users');
        Route::patch('users/{id}', 'patch')->name('v1.patch.users');
        Route::put('users/{id}', 'put')->name('v1.put.users');
        Route::delete('users/{id}', 'delete')->name('v1.delete.users');
        Route::post('users_datatables', 'datatables')->name('v1.datatable.users');
        Route::patch('users/{id}/approve', 'approve')->name('v1.approve.users');
    });
    Route::controller(AccountingMasterController::class)->group(function() {
        Route::get('accounting_masters/{id?}', 'get')->name('v1.get.accounting_masters');
        Route::post('accounting_masters', 'post')->name('v1.post.accounting_masters');
        Route::patch('accounting_masters/{id}', 'patch')->name('v1.patch.accounting_masters');
        Route::put('accounting_masters/{id}', 'put')->name('v1.put.accounting_masters');
        Route::delete('accounting_masters/{id}', 'delete')->name('v1.delete.accounting_masters');
        Route::post('accounting_masters_datatables', 'datatables')->name('v1.datatable.accounting_masters');
        Route::patch('accounting_masters/{id}/approve', 'approve')->name('v1.approve.accounting_masters');
    });
    Route::controller(BankAccountController::class)->group(function() {
        Route::get('bank_accounts/{id?}', 'get')->name('v1.get.bank_accounts');
        Route::post('bank_accounts', 'post')->name('v1.post.bank_accounts');
        Route::patch('bank_accounts/{id}', 'patch')->name('v1.patch.bank_accounts');
        Route::put('bank_accounts/{id}', 'put')->name('v1.put.bank_accounts');
        Route::delete('bank_accounts/{id}', 'delete')->name('v1.delete.bank_accounts');
        Route::post('bank_accounts_datatables', 'datatables')->name('v1.datatable.bank_accounts');
        Route::patch('bank_accounts/{id}/approve', 'approve')->name('v1.approve.bank_accounts');
    });
    Route::controller(ContactGroupController::class)->group(function() {
        Route::get('contact_groups/{id?}', 'get')->name('v1.get.contact_groups');
        Route::post('contact_groups', 'post')->name('v1.post.contact_groups');
        Route::patch('contact_groups/{id}', 'patch')->name('v1.patch.contact_groups');
        Route::put('contact_groups/{id}', 'put')->name('v1.put.contact_groups');
        Route::delete('contact_groups/{id}', 'delete')->name('v1.delete.contact_groups');
        Route::post('contact_groups_datatables', 'datatables')->name('v1.datatable.contact_groups');
        Route::patch('contact_groups/{id}/approve', 'approve')->name('v1.approve.contact_groups');
    });
    Route::controller(ContactController::class)->group(function() {
        Route::get('contacts/{id?}', 'get')->name('v1.get.contacts');
        Route::post('contacts', 'post')->name('v1.post.contacts');
        Route::patch('contacts/{id}', 'patch')->name('v1.patch.contacts');
        Route::put('contacts/{id}', 'put')->name('v1.put.contacts');
        Route::delete('contacts/{id}', 'delete')->name('v1.delete.contacts');
        Route::post('contacts_datatables', 'datatables')->name('v1.datatable.contacts');
        Route::patch('contacts/{id}/approve', 'approve')->name('v1.approve.contacts');
    });
    Route::controller(BranchController::class)->group(function() {
        Route::get('branches/{id?}', 'get')->name('v1.get.branches');
        Route::post('branches', 'post')->name('v1.post.branches');
        Route::patch('branches/{id}', 'patch')->name('v1.patch.branches');
        Route::put('branches/{id}', 'put')->name('v1.put.branches');
        Route::delete('branches/{id}', 'delete')->name('v1.delete.branches');
        Route::post('branches_datatables', 'datatables')->name('v1.datatable.branches');
        Route::patch('branches/{id}/approve', 'approve')->name('v1.approve.branches');
    });
    Route::controller(CurrencyController::class)->group(function() {
        Route::get('currencies/{id?}', 'get')->name('v1.get.currencies');
        Route::post('currencies', 'post')->name('v1.post.currencies');
        Route::patch('currencies/{id}', 'patch')->name('v1.patch.currencies');
        Route::put('currencies/{id}', 'put')->name('v1.put.currencies');
        Route::delete('currencies/{id}', 'delete')->name('v1.delete.currencies');
        Route::post('currencies_datatables', 'datatables')->name('v1.datatable.currencies');
        Route::patch('currencies/{id}/approve', 'approve')->name('v1.approve.currencies');
    });
    Route::controller(GeneralSettingController::class)->group(function() {
        Route::get('general_settings/{id?}', 'get')->name('v1.get.general_settings');
        Route::post('general_settings', 'post')->name('v1.post.general_settings');
        Route::patch('general_settings/{id}', 'patch')->name('v1.patch.general_settings');
        Route::put('general_settings/{id}', 'put')->name('v1.put.general_settings');
        Route::delete('general_settings/{id}', 'delete')->name('v1.delete.general_settings');
        Route::post('general_settings_datatables', 'datatables')->name('v1.datatable.general_settings');
        Route::patch('general_settings/{id}/approve', 'approve')->name('v1.approve.general_settings');
    });
    Route::controller(MediumController::class)->group(function() {
        Route::get('media/{id?}', 'get')->name('v1.get.media');
        Route::post('media', 'post')->name('v1.post.media');
        Route::patch('media/{id}', 'patch')->name('v1.patch.media');
        Route::put('media/{id}', 'put')->name('v1.put.media');
        Route::delete('media/{id}', 'delete')->name('v1.delete.media');
        Route::post('media_datatables', 'datatables')->name('v1.datatable.media');
        Route::patch('media/{id}/approve', 'approve')->name('v1.approve.media');
    });
    Route::controller(DefaultAccountController::class)->group(function() {
        Route::get('default_accounts/{id?}', 'get')->name('v1.get.default_accounts');
        Route::post('default_accounts', 'post')->name('v1.post.default_accounts');
        Route::patch('default_accounts/{id}', 'patch')->name('v1.patch.default_accounts');
        Route::put('default_accounts/{id}', 'put')->name('v1.put.default_accounts');
        Route::delete('default_accounts/{id}', 'delete')->name('v1.delete.default_accounts');
        Route::post('default_accounts_datatables', 'datatables')->name('v1.datatable.default_accounts');
        Route::patch('default_accounts/{id}/approve', 'approve')->name('v1.approve.default_accounts');
    });
    Route::controller(ContactGroupPointRuleController::class)->group(function() {
        Route::get('contact_group_point_rules/{id?}', 'get')->name('v1.get.contact_group_point_rules');
        Route::post('contact_group_point_rules', 'post')->name('v1.post.contact_group_point_rules');
        Route::patch('contact_group_point_rules/{id}', 'patch')->name('v1.patch.contact_group_point_rules');
        Route::put('contact_group_point_rules/{id}', 'put')->name('v1.put.contact_group_point_rules');
        Route::delete('contact_group_point_rules/{id}', 'delete')->name('v1.delete.contact_group_point_rules');
        Route::post('contact_group_point_rules_datatables', 'datatables')->name('v1.datatable.contact_group_point_rules');
        Route::patch('contact_group_point_rules/{id}/approve', 'approve')->name('v1.approve.contact_group_point_rules');
    });
    Route::controller(RewardPointController::class)->group(function() {
        Route::get('reward_points/{id?}', 'get')->name('v1.get.reward_points');
        Route::post('reward_points', 'post')->name('v1.post.reward_points');
        Route::patch('reward_points/{id}', 'patch')->name('v1.patch.reward_points');
        Route::put('reward_points/{id}', 'put')->name('v1.put.reward_points');
        Route::delete('reward_points/{id}', 'delete')->name('v1.delete.reward_points');
        Route::post('reward_points_datatables', 'datatables')->name('v1.datatable.reward_points');
        Route::patch('reward_points/{id}/approve', 'approve')->name('v1.approve.reward_points');
    });
    Route::controller(TaxController::class)->group(function() {
        Route::get('taxes/{id?}', 'get')->name('v1.get.taxes');
        Route::post('taxes', 'post')->name('v1.post.taxes');
        Route::patch('taxes/{id}', 'patch')->name('v1.patch.taxes');
        Route::put('taxes/{id}', 'put')->name('v1.put.taxes');
        Route::delete('taxes/{id}', 'delete')->name('v1.delete.taxes');
        Route::post('taxes_datatables', 'datatables')->name('v1.datatable.taxes');
        Route::patch('taxes/{id}/approve', 'approve')->name('v1.approve.taxes');
    });
    Route::controller(UnitController::class)->group(function() {
        Route::get('units/{id?}', 'get')->name('v1.get.units');
        Route::post('units', 'post')->name('v1.post.units');
        Route::patch('units/{id}', 'patch')->name('v1.patch.units');
        Route::put('units/{id}', 'put')->name('v1.put.units');
        Route::delete('units/{id}', 'delete')->name('v1.delete.units');
        Route::post('units_datatables', 'datatables')->name('v1.datatable.units');
        Route::patch('units/{id}/approve', 'approve')->name('v1.approve.units');
    });
    Route::controller(BaseUnitConversionController::class)->group(function() {
        Route::get('base_unit_conversions/{id?}', 'get')->name('v1.get.base_unit_conversions');
        Route::post('base_unit_conversions', 'post')->name('v1.post.base_unit_conversions');
        Route::patch('base_unit_conversions/{id}', 'patch')->name('v1.patch.base_unit_conversions');
        Route::put('base_unit_conversions/{id}', 'put')->name('v1.put.base_unit_conversions');
        Route::delete('base_unit_conversions/{id}', 'delete')->name('v1.delete.base_unit_conversions');
        Route::post('base_unit_conversions_datatables', 'datatables')->name('v1.datatable.base_unit_conversions');
        Route::patch('base_unit_conversions/{id}/approve', 'approve')->name('v1.approve.base_unit_conversions');
    });
    Route::controller(VariantOptionController::class)->group(function() {
        Route::get('variant_options/{id?}', 'get')->name('v1.get.variant_options');
        Route::post('variant_options', 'post')->name('v1.post.variant_options');
        Route::patch('variant_options/{id}', 'patch')->name('v1.patch.variant_options');
        Route::put('variant_options/{id}', 'put')->name('v1.put.variant_options');
        Route::delete('variant_options/{id}', 'delete')->name('v1.delete.variant_options');
        Route::post('variant_options_datatables', 'datatables')->name('v1.datatable.variant_options');
        Route::patch('variant_options/{id}/approve', 'approve')->name('v1.approve.variant_options');
    });
    Route::controller(VariantController::class)->group(function() {
        Route::get('variants/{id?}', 'get')->name('v1.get.variants');
        Route::post('variants', 'post')->name('v1.post.variants');
        Route::patch('variants/{id}', 'patch')->name('v1.patch.variants');
        Route::put('variants/{id}', 'put')->name('v1.put.variants');
        Route::delete('variants/{id}', 'delete')->name('v1.delete.variants');
        Route::post('variants_datatables', 'datatables')->name('v1.datatable.variants');
        Route::patch('variants/{id}/approve', 'approve')->name('v1.approve.variants');
    });
    Route::controller(ProductCategoryController::class)->group(function() {
        Route::get('product_categories/{id?}', 'get')->name('v1.get.product_categories');
        Route::post('product_categories', 'post')->name('v1.post.product_categories');
        Route::patch('product_categories/{id}', 'patch')->name('v1.patch.product_categories');
        Route::put('product_categories/{id}', 'put')->name('v1.put.product_categories');
        Route::delete('product_categories/{id}', 'delete')->name('v1.delete.product_categories');
        Route::post('product_categories_datatables', 'datatables')->name('v1.datatable.product_categories');
        Route::patch('product_categories/{id}/approve', 'approve')->name('v1.approve.product_categories');
    });
    Route::controller(ProductController::class)->group(function() {
        Route::get('products/{id?}', 'get')->name('v1.get.products');
        Route::post('products', 'post')->name('v1.post.products');
        Route::patch('products/{id}', 'patch')->name('v1.patch.products');
        Route::put('products/{id}', 'put')->name('v1.put.products');
        Route::delete('products/{id}', 'delete')->name('v1.delete.products');
        Route::post('products_datatables', 'datatables')->name('v1.datatable.products');
        Route::patch('products/{id}/approve', 'approve')->name('v1.approve.products');
    });
    Route::controller(ProductVariantController::class)->group(function() {
        Route::get('product_variants/{id?}', 'get')->name('v1.get.product_variants');
        Route::post('product_variants', 'post')->name('v1.post.product_variants');
        Route::patch('product_variants/{id}', 'patch')->name('v1.patch.product_variants');
        Route::put('product_variants/{id}', 'put')->name('v1.put.product_variants');
        Route::delete('product_variants/{id}', 'delete')->name('v1.delete.product_variants');
        Route::post('product_variants_datatables', 'datatables')->name('v1.datatable.product_variants');
        Route::patch('product_variants/{id}/approve', 'approve')->name('v1.approve.product_variants');
    });
    Route::controller(ProductUnitConversionController::class)->group(function() {
        Route::get('product_unit_conversions/{id?}', 'get')->name('v1.get.product_unit_conversions');
        Route::post('product_unit_conversions', 'post')->name('v1.post.product_unit_conversions');
        Route::patch('product_unit_conversions/{id}', 'patch')->name('v1.patch.product_unit_conversions');
        Route::put('product_unit_conversions/{id}', 'put')->name('v1.put.product_unit_conversions');
        Route::delete('product_unit_conversions/{id}', 'delete')->name('v1.delete.product_unit_conversions');
        Route::post('product_unit_conversions_datatables', 'datatables')->name('v1.datatable.product_unit_conversions');
        Route::patch('product_unit_conversions/{id}/approve', 'approve')->name('v1.approve.product_unit_conversions');
    });
    Route::controller(ProductSkuController::class)->group(function() {
        Route::get('product_skus/{id?}', 'get')->name('v1.get.product_skus');
        Route::post('product_skus', 'post')->name('v1.post.product_skus');
        Route::patch('product_skus/{id}', 'patch')->name('v1.patch.product_skus');
        Route::put('product_skus/{id}', 'put')->name('v1.put.product_skus');
        Route::delete('product_skus/{id}', 'delete')->name('v1.delete.product_skus');
        Route::post('product_skus_datatables', 'datatables')->name('v1.datatable.product_skus');
        Route::patch('product_skus/{id}/approve', 'approve')->name('v1.approve.product_skus');
    });
    Route::controller(ProductSkuVariantController::class)->group(function() {
        Route::get('product_sku_variants/{id?}', 'get')->name('v1.get.product_sku_variants');
        Route::post('product_sku_variants', 'post')->name('v1.post.product_sku_variants');
        Route::patch('product_sku_variants/{id}', 'patch')->name('v1.patch.product_sku_variants');
        Route::put('product_sku_variants/{id}', 'put')->name('v1.put.product_sku_variants');
        Route::delete('product_sku_variants/{id}', 'delete')->name('v1.delete.product_sku_variants');
        Route::post('product_sku_variants_datatables', 'datatables')->name('v1.datatable.product_sku_variants');
        Route::patch('product_sku_variants/{id}/approve', 'approve')->name('v1.approve.product_sku_variants');
    });
    Route::controller(ProductMultiPriceController::class)->group(function() {
        Route::get('product_multi_prices/{id?}', 'get')->name('v1.get.product_multi_prices');
        Route::post('product_multi_prices', 'post')->name('v1.post.product_multi_prices');
        Route::patch('product_multi_prices/{id}', 'patch')->name('v1.patch.product_multi_prices');
        Route::put('product_multi_prices/{id}', 'put')->name('v1.put.product_multi_prices');
        Route::delete('product_multi_prices/{id}', 'delete')->name('v1.delete.product_multi_prices');
        Route::post('product_multi_prices_datatables', 'datatables')->name('v1.datatable.product_multi_prices');
        Route::patch('product_multi_prices/{id}/approve', 'approve')->name('v1.approve.product_multi_prices');
    });
    Route::controller(ProductHistoryController::class)->group(function() {
        Route::get('product_histories/{id?}', 'get')->name('v1.get.product_histories');
        Route::post('product_histories', 'post')->name('v1.post.product_histories');
        Route::patch('product_histories/{id}', 'patch')->name('v1.patch.product_histories');
        Route::put('product_histories/{id}', 'put')->name('v1.put.product_histories');
        Route::delete('product_histories/{id}', 'delete')->name('v1.delete.product_histories');
        Route::post('product_histories_datatables', 'datatables')->name('v1.datatable.product_histories');
        Route::patch('product_histories/{id}/approve', 'approve')->name('v1.approve.product_histories');
    });
    Route::controller(ProductClosingController::class)->group(function() {
        Route::get('product_closings/{id?}', 'get')->name('v1.get.product_closings');
        Route::post('product_closings', 'post')->name('v1.post.product_closings');
        Route::patch('product_closings/{id}', 'patch')->name('v1.patch.product_closings');
        Route::put('product_closings/{id}', 'put')->name('v1.put.product_closings');
        Route::delete('product_closings/{id}', 'delete')->name('v1.delete.product_closings');
        Route::post('product_closings_datatables', 'datatables')->name('v1.datatable.product_closings');
        Route::patch('product_closings/{id}/approve', 'approve')->name('v1.approve.product_closings');
    });
    Route::controller(PointHistoryController::class)->group(function() {
        Route::get('point_histories/{id?}', 'get')->name('v1.get.point_histories');
        Route::post('point_histories', 'post')->name('v1.post.point_histories');
        Route::patch('point_histories/{id}', 'patch')->name('v1.patch.point_histories');
        Route::put('point_histories/{id}', 'put')->name('v1.put.point_histories');
        Route::delete('point_histories/{id}', 'delete')->name('v1.delete.point_histories');
        Route::post('point_histories_datatables', 'datatables')->name('v1.datatable.point_histories');
        Route::patch('point_histories/{id}/approve', 'approve')->name('v1.approve.point_histories');
    });
    Route::controller(AccountingJournalController::class)->group(function() {
        Route::get('accounting_journals/{id?}', 'get')->name('v1.get.accounting_journals');
        Route::post('accounting_journals', 'post')->name('v1.post.accounting_journals');
        Route::patch('accounting_journals/{id}', 'patch')->name('v1.patch.accounting_journals');
        Route::put('accounting_journals/{id}', 'put')->name('v1.put.accounting_journals');
        Route::delete('accounting_journals/{id}', 'delete')->name('v1.delete.accounting_journals');
        Route::post('accounting_journals_datatables', 'datatables')->name('v1.datatable.accounting_journals');
        Route::patch('accounting_journals/{id}/approve', 'approve')->name('v1.approve.accounting_journals');
    });
    Route::controller(SalesInvoiceController::class)->group(function() {
        Route::get('sales_invoices/{id?}', 'get')->name('v1.get.sales_invoices');
        Route::post('sales_invoices', 'post')->name('v1.post.sales_invoices');
        Route::patch('sales_invoices/{id}', 'patch')->name('v1.patch.sales_invoices');
        Route::put('sales_invoices/{id}', 'put')->name('v1.put.sales_invoices');
        Route::delete('sales_invoices/{id}', 'delete')->name('v1.delete.sales_invoices');
        Route::post('sales_invoices_datatables', 'datatables')->name('v1.datatable.sales_invoices');
        Route::patch('sales_invoices/{id}/approve', 'approve')->name('v1.approve.sales_invoices');
    });
    Route::controller(SalesInvoiceDetailController::class)->group(function() {
        Route::get('sales_invoice_details/{id?}', 'get')->name('v1.get.sales_invoice_details');
        Route::post('sales_invoice_details', 'post')->name('v1.post.sales_invoice_details');
        Route::patch('sales_invoice_details/{id}', 'patch')->name('v1.patch.sales_invoice_details');
        Route::put('sales_invoice_details/{id}', 'put')->name('v1.put.sales_invoice_details');
        Route::delete('sales_invoice_details/{id}', 'delete')->name('v1.delete.sales_invoice_details');
        Route::post('sales_invoice_details_datatables', 'datatables')->name('v1.datatable.sales_invoice_details');
        Route::patch('sales_invoice_details/{id}/approve', 'approve')->name('v1.approve.sales_invoice_details');
    });
    Route::controller(SalesInvoiceDetailVariantController::class)->group(function() {
        Route::get('sales_invoice_detail_variants/{id?}', 'get')->name('v1.get.sales_invoice_detail_variants');
        Route::post('sales_invoice_detail_variants', 'post')->name('v1.post.sales_invoice_detail_variants');
        Route::patch('sales_invoice_detail_variants/{id}', 'patch')->name('v1.patch.sales_invoice_detail_variants');
        Route::put('sales_invoice_detail_variants/{id}', 'put')->name('v1.put.sales_invoice_detail_variants');
        Route::delete('sales_invoice_detail_variants/{id}', 'delete')->name('v1.delete.sales_invoice_detail_variants');
        Route::post('sales_invoice_detail_variants_datatables', 'datatables')->name('v1.datatable.sales_invoice_detail_variants');
        Route::patch('sales_invoice_detail_variants/{id}/approve', 'approve')->name('v1.approve.sales_invoice_detail_variants');
    });
    Route::controller(WarehouseController::class)->group(function() {
        Route::get('warehouses/{id?}', 'get')->name('v1.get.warehouses');
        Route::post('warehouses', 'post')->name('v1.post.warehouses');
        Route::patch('warehouses/{id}', 'patch')->name('v1.patch.warehouses');
        Route::put('warehouses/{id}', 'put')->name('v1.put.warehouses');
        Route::delete('warehouses/{id}', 'delete')->name('v1.delete.warehouses');
        Route::post('warehouses_datatables', 'datatables')->name('v1.datatable.warehouses');
        Route::patch('warehouses/{id}/approve', 'approve')->name('v1.approve.warehouses');
    });
    Route::controller(PermissionController::class)->group(function() {
        Route::get('permissions/{id?}', 'get')->name('v1.get.permissions');
        Route::post('permissions', 'post')->name('v1.post.permissions');
        Route::patch('permissions/{id}', 'patch')->name('v1.patch.permissions');
        Route::put('permissions/{id}', 'put')->name('v1.put.permissions');
        Route::delete('permissions/{id}', 'delete')->name('v1.delete.permissions');
        Route::post('permissions_datatables', 'datatables')->name('v1.datatable.permissions');
        Route::patch('permissions/{id}/approve', 'approve')->name('v1.approve.permissions');
    });
    Route::controller(ModelHasPermissionController::class)->group(function() {
        Route::get('model_has_permissions/{id?}', 'get')->name('v1.get.model_has_permissions');
        Route::post('model_has_permissions', 'post')->name('v1.post.model_has_permissions');
        Route::patch('model_has_permissions/{id}', 'patch')->name('v1.patch.model_has_permissions');
        Route::put('model_has_permissions/{id}', 'put')->name('v1.put.model_has_permissions');
        Route::delete('model_has_permissions/{id}', 'delete')->name('v1.delete.model_has_permissions');
        Route::post('model_has_permissions_datatables', 'datatables')->name('v1.datatable.model_has_permissions');
        Route::patch('model_has_permissions/{id}/approve', 'approve')->name('v1.approve.model_has_permissions');
    });
    Route::controller(RoleController::class)->group(function() {
        Route::get('roles/{id?}', 'get')->name('v1.get.roles');
        Route::post('roles', 'post')->name('v1.post.roles');
        Route::patch('roles/{id}', 'patch')->name('v1.patch.roles');
        Route::put('roles/{id}', 'put')->name('v1.put.roles');
        Route::delete('roles/{id}', 'delete')->name('v1.delete.roles');
        Route::post('roles_datatables', 'datatables')->name('v1.datatable.roles');
        Route::patch('roles/{id}/approve', 'approve')->name('v1.approve.roles');
    });
    Route::controller(ModelHasRoleController::class)->group(function() {
        Route::get('model_has_roles/{id?}', 'get')->name('v1.get.model_has_roles');
        Route::post('model_has_roles', 'post')->name('v1.post.model_has_roles');
        Route::patch('model_has_roles/{id}', 'patch')->name('v1.patch.model_has_roles');
        Route::put('model_has_roles/{id}', 'put')->name('v1.put.model_has_roles');
        Route::delete('model_has_roles/{id}', 'delete')->name('v1.delete.model_has_roles');
        Route::post('model_has_roles_datatables', 'datatables')->name('v1.datatable.model_has_roles');
        Route::patch('model_has_roles/{id}/approve', 'approve')->name('v1.approve.model_has_roles');
    });
    Route::controller(RoleHasPermissionController::class)->group(function() {
        Route::get('role_has_permissions/{id?}', 'get')->name('v1.get.role_has_permissions');
        Route::post('role_has_permissions', 'post')->name('v1.post.role_has_permissions');
        Route::patch('role_has_permissions/{id}', 'patch')->name('v1.patch.role_has_permissions');
        Route::put('role_has_permissions/{id}', 'put')->name('v1.put.role_has_permissions');
        Route::delete('role_has_permissions/{id}', 'delete')->name('v1.delete.role_has_permissions');
        Route::post('role_has_permissions_datatables', 'datatables')->name('v1.datatable.role_has_permissions');
        Route::patch('role_has_permissions/{id}/approve', 'approve')->name('v1.approve.role_has_permissions');
    });
    
    Route::controller(PointOfSalesController::class)->prefix('pos')->group(function() {
        Route::get('holds', 'getHoldData')->name('v1.pos.holds.get');
        Route::post('holds', 'hold')->name('v1.pos.holds.post');
        Route::post('payments', 'payment')->name('v1.pos.payments');
        Route::get('generate_ref_numbers', 'getRefNumber')->name('v1.pos.get_ref_number');
        Route::get('discount_point_exchange', 'discountPointExchange')->name('v1.pos.discount_point_exchange');
        Route::post('login', 'login')->name('v1.pos.login');
    });

    Route::prefix('sync')->group(function (){
        Route::post('/', [ProductController::class, 'syncAll'])->name('sync.all');
        Route::get('/accounting_master', [AccountingMasterController::class, 'syncToLocal'])->name('sync.accounting_master');
        Route::get('/bank_accounts', [BankAccountController::class, 'syncToLocal'])->name('sync.bank_accounts');
        Route::get('/base_unit_conversions', [BaseUnitConversionController::class, 'syncToLocal'])->name('sync.base_unit_conversions');
        Route::get('/branches', [BranchController::class, 'syncToLocal'])->name('sync.branches');
        Route::get('/currencies', [CurrencyController::class, 'syncToLocal'])->name('sync.currencies');
        Route::get('/contacts', [ContactController::class, 'syncToLocal'])->name('sync.contacts');
        Route::get('/contact_groups', [ContactGroupController::class, 'syncToLocal'])->name('sync.contact_groups');
        Route::get('/contact_point_rules', [ContactGroupPointRuleController::class, 'syncToLocal'])->name('sync.contact_point_rules');
        Route::get('/default_accounts', [DefaultAccountController::class, 'syncToLocal'])->name('sync.default_accounts');
        Route::get('/general_settings', [GeneralSettingController::class, 'syncToLocal'])->name('sync.general_settings');
        Route::get('/media', [MediumController::class, 'syncToLocal'])->name('sync.media');
        Route::get('/products', [ProductController::class, 'syncToLocal'])->name('sync.products');
        Route::get('/product_categories', [ProductCategoryController::class, 'syncToLocal'])->name('sync.product_categories');
        Route::get('/product_multi_prices', [ProductMultiPriceController::class, 'syncToLocal'])->name('sync.product_multi_prices');
        Route::get('/product_skus', [ProductSkuController::class, 'syncToLocal'])->name('sync.product_skus');
        Route::get('/product_sku_variants', [ProductSkuVariantController::class, 'syncToLocal'])->name('sync.product_sku_variants');
        Route::get('/product_stock', [ProductController::class, 'getStockDatatable'])->name('sync.product_stock');
        Route::get('/product_unit_conversions', [ProductUnitConversionController::class, 'syncToLocal'])->name('sync.product_unit_conversions');
        Route::get('/product_variants', [ProductVariantController::class, 'syncToLocal'])->name('sync.product_variants');
        Route::get('/reward_points', [RewardPointController::class, 'syncToLocal'])->name('sync.reward_points');
        Route::get('/roles', [RoleController::class, 'syncToLocal'])->name('sync.roles');
        Route::get('/taxes', [TaxController::class, 'syncToLocal'])->name('sync.taxes');
        Route::get('/units', [UnitController::class, 'syncToLocal'])->name('sync.units');
        Route::get('/variants', [VariantController::class, 'syncToLocal'])->name('sync.variants');
        Route::get('/variant_options', [VariantOptionController::class, 'syncToLocal'])->name('sync.variant_options');
        Route::get('/warehouses', [WarehouseController::class, 'syncToLocal'])->name('sync.warehouses');
        Route::post('/sales_invoices', [SalesInvoiceController::class, 'syncToServer'])->name('sync.post_sales_invoices');
        Route::get('/stock_cards', [ProductClosingController::class, 'getStockCard'])->name('sync.stock_card');
        Route::post('/sync_sales_invoices', [SalesInvoiceController::class, 'syncSalesInvoices'])->name('sync.sales_invoices');
    });

    Route::get('/persib_bandung_juara', function (Request $request) {
        // $halo = 'asd';
        return response()->json($halo);
    });

    // ---- Route Controller Generator ----
});
