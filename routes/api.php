<?php
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PointOfSalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AccountingMasterController;
use App\Http\Controllers\API\BankAccountController;
use App\Http\Controllers\API\ContactGroupController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\BranchController;
use App\Http\Controllers\API\CurrencyController;
use App\Http\Controllers\API\GeneralSettingController;
use App\Http\Controllers\API\MediumController;
use App\Http\Controllers\API\DefaultAccountController;
use App\Http\Controllers\API\ContactGroupPointRuleController;
use App\Http\Controllers\API\RewardPointController;
use App\Http\Controllers\API\TaxController;
use App\Http\Controllers\API\UnitController;
use App\Http\Controllers\API\BaseUnitConversionController;
use App\Http\Controllers\API\VariantOptionController;
use App\Http\Controllers\API\VariantController;
use App\Http\Controllers\API\ProductCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ProductVariantController;
use App\Http\Controllers\API\ProductUnitConversionController;
use App\Http\Controllers\API\ProductSkuController;
use App\Http\Controllers\API\ProductSkuVariantController;
use App\Http\Controllers\API\ProductMultiPriceController;
use App\Http\Controllers\API\ProductHistoryController;
use App\Http\Controllers\API\ProductClosingController;
use App\Http\Controllers\API\PointHistoryController;
use App\Http\Controllers\API\AccountingJournalController;
use App\Http\Controllers\API\SalesInvoiceController;
use App\Http\Controllers\API\SalesInvoiceDetailController;
use App\Http\Controllers\API\SalesInvoiceDetailVariantController;
use App\Http\Controllers\API\WarehouseController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\ModelHasPermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\ModelHasRoleController;
use App\Http\Controllers\API\RoleHasPermissionController;
// ---- Route Use Generator ----
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'setup.config'])->group(function () {
    Route::controller(UserController::class)->group(function() {
        Route::get('users/{id?}', 'get')->name('get.users');
        Route::post('users', 'post')->name('post.users');
        Route::patch('users/{id}', 'patch')->name('patch.users');
        Route::put('users/{id}', 'put')->name('put.users');
        Route::delete('users/{id}', 'delete')->name('delete.users');
        Route::post('users_datatables', 'datatables')->name('datatable.users');
        Route::patch('users/{id}/approve', 'approve')->name('approve.users');
    });
    Route::controller(AccountingMasterController::class)->group(function() {
        Route::get('accounting_masters/{id?}', 'get')->name('get.accounting_masters');
        Route::post('accounting_masters', 'post')->name('post.accounting_masters');
        Route::patch('accounting_masters/{id}', 'patch')->name('patch.accounting_masters');
        Route::put('accounting_masters/{id}', 'put')->name('put.accounting_masters');
        Route::delete('accounting_masters/{id}', 'delete')->name('delete.accounting_masters');
        Route::post('accounting_masters_datatables', 'datatables')->name('datatable.accounting_masters');
        Route::patch('accounting_masters/{id}/approve', 'approve')->name('approve.accounting_masters');
    });
    Route::controller(BankAccountController::class)->group(function() {
        Route::get('bank_accounts/{id?}', 'get')->name('get.bank_accounts');
        Route::post('bank_accounts', 'post')->name('post.bank_accounts');
        Route::patch('bank_accounts/{id}', 'patch')->name('patch.bank_accounts');
        Route::put('bank_accounts/{id}', 'put')->name('put.bank_accounts');
        Route::delete('bank_accounts/{id}', 'delete')->name('delete.bank_accounts');
        Route::post('bank_accounts_datatables', 'datatables')->name('datatable.bank_accounts');
        Route::patch('bank_accounts/{id}/approve', 'approve')->name('approve.bank_accounts');
    });
    Route::controller(ContactGroupController::class)->group(function() {
        Route::get('contact_groups/{id?}', 'get')->name('get.contact_groups');
        Route::post('contact_groups', 'post')->name('post.contact_groups');
        Route::patch('contact_groups/{id}', 'patch')->name('patch.contact_groups');
        Route::put('contact_groups/{id}', 'put')->name('put.contact_groups');
        Route::delete('contact_groups/{id}', 'delete')->name('delete.contact_groups');
        Route::post('contact_groups_datatables', 'datatables')->name('datatable.contact_groups');
        Route::patch('contact_groups/{id}/approve', 'approve')->name('approve.contact_groups');
    });
    Route::controller(ContactController::class)->group(function() {
        Route::get('contacts/{id?}', 'get')->name('get.contacts');
        Route::post('contacts', 'post')->name('post.contacts');
        Route::patch('contacts/{id}', 'patch')->name('patch.contacts');
        Route::put('contacts/{id}', 'put')->name('put.contacts');
        Route::delete('contacts/{id}', 'delete')->name('delete.contacts');
        Route::post('contacts_datatables', 'datatables')->name('datatable.contacts');
        Route::patch('contacts/{id}/approve', 'approve')->name('approve.contacts');
    });
    Route::controller(BranchController::class)->group(function() {
        Route::get('branches/{id?}', 'get')->name('get.branches');
        Route::post('branches', 'post')->name('post.branches');
        Route::patch('branches/{id}', 'patch')->name('patch.branches');
        Route::put('branches/{id}', 'put')->name('put.branches');
        Route::delete('branches/{id}', 'delete')->name('delete.branches');
        Route::post('branches_datatables', 'datatables')->name('datatable.branches');
        Route::patch('branches/{id}/approve', 'approve')->name('approve.branches');
    });
    Route::controller(CurrencyController::class)->group(function() {
        Route::get('currencies/{id?}', 'get')->name('get.currencies');
        Route::post('currencies', 'post')->name('post.currencies');
        Route::patch('currencies/{id}', 'patch')->name('patch.currencies');
        Route::put('currencies/{id}', 'put')->name('put.currencies');
        Route::delete('currencies/{id}', 'delete')->name('delete.currencies');
        Route::post('currencies_datatables', 'datatables')->name('datatable.currencies');
        Route::patch('currencies/{id}/approve', 'approve')->name('approve.currencies');
    });
    Route::controller(GeneralSettingController::class)->group(function() {
        Route::get('general_settings/{id?}', 'get')->name('get.general_settings');
        Route::post('general_settings', 'post')->name('post.general_settings');
        Route::patch('general_settings/{id}', 'patch')->name('patch.general_settings');
        Route::put('general_settings/{id}', 'put')->name('put.general_settings');
        Route::delete('general_settings/{id}', 'delete')->name('delete.general_settings');
        Route::post('general_settings_datatables', 'datatables')->name('datatable.general_settings');
        Route::patch('general_settings/{id}/approve', 'approve')->name('approve.general_settings');
    });
    Route::controller(MediumController::class)->group(function() {
        Route::get('media/{id?}', 'get')->name('get.media');
        Route::post('media', 'post')->name('post.media');
        Route::patch('media/{id}', 'patch')->name('patch.media');
        Route::put('media/{id}', 'put')->name('put.media');
        Route::delete('media/{id}', 'delete')->name('delete.media');
        Route::post('media_datatables', 'datatables')->name('datatable.media');
        Route::patch('media/{id}/approve', 'approve')->name('approve.media');
    });
    Route::controller(DefaultAccountController::class)->group(function() {
        Route::get('default_accounts/{id?}', 'get')->name('get.default_accounts');
        Route::post('default_accounts', 'post')->name('post.default_accounts');
        Route::patch('default_accounts/{id}', 'patch')->name('patch.default_accounts');
        Route::put('default_accounts/{id}', 'put')->name('put.default_accounts');
        Route::delete('default_accounts/{id}', 'delete')->name('delete.default_accounts');
        Route::post('default_accounts_datatables', 'datatables')->name('datatable.default_accounts');
        Route::patch('default_accounts/{id}/approve', 'approve')->name('approve.default_accounts');
    });
    Route::controller(ContactGroupPointRuleController::class)->group(function() {
        Route::get('contact_group_point_rules/{id?}', 'get')->name('get.contact_group_point_rules');
        Route::post('contact_group_point_rules', 'post')->name('post.contact_group_point_rules');
        Route::patch('contact_group_point_rules/{id}', 'patch')->name('patch.contact_group_point_rules');
        Route::put('contact_group_point_rules/{id}', 'put')->name('put.contact_group_point_rules');
        Route::delete('contact_group_point_rules/{id}', 'delete')->name('delete.contact_group_point_rules');
        Route::post('contact_group_point_rules_datatables', 'datatables')->name('datatable.contact_group_point_rules');
        Route::patch('contact_group_point_rules/{id}/approve', 'approve')->name('approve.contact_group_point_rules');
    });
    Route::controller(RewardPointController::class)->group(function() {
        Route::get('reward_points/{id?}', 'get')->name('get.reward_points');
        Route::post('reward_points', 'post')->name('post.reward_points');
        Route::patch('reward_points/{id}', 'patch')->name('patch.reward_points');
        Route::put('reward_points/{id}', 'put')->name('put.reward_points');
        Route::delete('reward_points/{id}', 'delete')->name('delete.reward_points');
        Route::post('reward_points_datatables', 'datatables')->name('datatable.reward_points');
        Route::patch('reward_points/{id}/approve', 'approve')->name('approve.reward_points');
    });
    Route::controller(TaxController::class)->group(function() {
        Route::get('taxes/{id?}', 'get')->name('get.taxes');
        Route::post('taxes', 'post')->name('post.taxes');
        Route::patch('taxes/{id}', 'patch')->name('patch.taxes');
        Route::put('taxes/{id}', 'put')->name('put.taxes');
        Route::delete('taxes/{id}', 'delete')->name('delete.taxes');
        Route::post('taxes_datatables', 'datatables')->name('datatable.taxes');
        Route::patch('taxes/{id}/approve', 'approve')->name('approve.taxes');
    });
    Route::controller(UnitController::class)->group(function() {
        Route::get('units/{id?}', 'get')->name('get.units');
        Route::post('units', 'post')->name('post.units');
        Route::patch('units/{id}', 'patch')->name('patch.units');
        Route::put('units/{id}', 'put')->name('put.units');
        Route::delete('units/{id}', 'delete')->name('delete.units');
        Route::post('units_datatables', 'datatables')->name('datatable.units');
        Route::patch('units/{id}/approve', 'approve')->name('approve.units');
    });
    Route::controller(BaseUnitConversionController::class)->group(function() {
        Route::get('base_unit_conversions/{id?}', 'get')->name('get.base_unit_conversions');
        Route::post('base_unit_conversions', 'post')->name('post.base_unit_conversions');
        Route::patch('base_unit_conversions/{id}', 'patch')->name('patch.base_unit_conversions');
        Route::put('base_unit_conversions/{id}', 'put')->name('put.base_unit_conversions');
        Route::delete('base_unit_conversions/{id}', 'delete')->name('delete.base_unit_conversions');
        Route::post('base_unit_conversions_datatables', 'datatables')->name('datatable.base_unit_conversions');
        Route::patch('base_unit_conversions/{id}/approve', 'approve')->name('approve.base_unit_conversions');
    });
    Route::controller(VariantOptionController::class)->group(function() {
        Route::get('variant_options/{id?}', 'get')->name('get.variant_options');
        Route::post('variant_options', 'post')->name('post.variant_options');
        Route::patch('variant_options/{id}', 'patch')->name('patch.variant_options');
        Route::put('variant_options/{id}', 'put')->name('put.variant_options');
        Route::delete('variant_options/{id}', 'delete')->name('delete.variant_options');
        Route::post('variant_options_datatables', 'datatables')->name('datatable.variant_options');
        Route::patch('variant_options/{id}/approve', 'approve')->name('approve.variant_options');
    });
    Route::controller(VariantController::class)->group(function() {
        Route::get('variants/{id?}', 'get')->name('get.variants');
        Route::post('variants', 'post')->name('post.variants');
        Route::patch('variants/{id}', 'patch')->name('patch.variants');
        Route::put('variants/{id}', 'put')->name('put.variants');
        Route::delete('variants/{id}', 'delete')->name('delete.variants');
        Route::post('variants_datatables', 'datatables')->name('datatable.variants');
        Route::patch('variants/{id}/approve', 'approve')->name('approve.variants');
    });
    Route::controller(ProductCategoryController::class)->group(function() {
        Route::get('product_categories/{id?}', 'get')->name('get.product_categories');
        Route::post('product_categories', 'post')->name('post.product_categories');
        Route::patch('product_categories/{id}', 'patch')->name('patch.product_categories');
        Route::put('product_categories/{id}', 'put')->name('put.product_categories');
        Route::delete('product_categories/{id}', 'delete')->name('delete.product_categories');
        Route::post('product_categories_datatables', 'datatables')->name('datatable.product_categories');
        Route::patch('product_categories/{id}/approve', 'approve')->name('approve.product_categories');
    });
    Route::controller(ProductController::class)->group(function() {
        Route::get('products/{id?}', 'get')->name('get.products');
        Route::post('products', 'post')->name('post.products');
        Route::patch('products/{id}', 'patch')->name('patch.products');
        Route::put('products/{id}', 'put')->name('put.products');
        Route::delete('products/{id}', 'delete')->name('delete.products');
        Route::post('products_datatables', 'datatables')->name('datatable.products');
        Route::patch('products/{id}/approve', 'approve')->name('approve.products');
    });
    Route::controller(ProductVariantController::class)->group(function() {
        Route::get('product_variants/{id?}', 'get')->name('get.product_variants');
        Route::post('product_variants', 'post')->name('post.product_variants');
        Route::patch('product_variants/{id}', 'patch')->name('patch.product_variants');
        Route::put('product_variants/{id}', 'put')->name('put.product_variants');
        Route::delete('product_variants/{id}', 'delete')->name('delete.product_variants');
        Route::post('product_variants_datatables', 'datatables')->name('datatable.product_variants');
        Route::patch('product_variants/{id}/approve', 'approve')->name('approve.product_variants');
    });
    Route::controller(ProductUnitConversionController::class)->group(function() {
        Route::get('product_unit_conversions/{id?}', 'get')->name('get.product_unit_conversions');
        Route::post('product_unit_conversions', 'post')->name('post.product_unit_conversions');
        Route::patch('product_unit_conversions/{id}', 'patch')->name('patch.product_unit_conversions');
        Route::put('product_unit_conversions/{id}', 'put')->name('put.product_unit_conversions');
        Route::delete('product_unit_conversions/{id}', 'delete')->name('delete.product_unit_conversions');
        Route::post('product_unit_conversions_datatables', 'datatables')->name('datatable.product_unit_conversions');
        Route::patch('product_unit_conversions/{id}/approve', 'approve')->name('approve.product_unit_conversions');
    });
    Route::controller(ProductSkuController::class)->group(function() {
        Route::get('product_skus/{id?}', 'get')->name('get.product_skus');
        Route::post('product_skus', 'post')->name('post.product_skus');
        Route::patch('product_skus/{id}', 'patch')->name('patch.product_skus');
        Route::put('product_skus/{id}', 'put')->name('put.product_skus');
        Route::delete('product_skus/{id}', 'delete')->name('delete.product_skus');
        Route::post('product_skus_datatables', 'datatables')->name('datatable.product_skus');
        Route::patch('product_skus/{id}/approve', 'approve')->name('approve.product_skus');
    });
    Route::controller(ProductSkuVariantController::class)->group(function() {
        Route::get('product_sku_variants/{id?}', 'get')->name('get.product_sku_variants');
        Route::post('product_sku_variants', 'post')->name('post.product_sku_variants');
        Route::patch('product_sku_variants/{id}', 'patch')->name('patch.product_sku_variants');
        Route::put('product_sku_variants/{id}', 'put')->name('put.product_sku_variants');
        Route::delete('product_sku_variants/{id}', 'delete')->name('delete.product_sku_variants');
        Route::post('product_sku_variants_datatables', 'datatables')->name('datatable.product_sku_variants');
        Route::patch('product_sku_variants/{id}/approve', 'approve')->name('approve.product_sku_variants');
    });
    Route::controller(ProductMultiPriceController::class)->group(function() {
        Route::get('product_multi_prices/{id?}', 'get')->name('get.product_multi_prices');
        Route::post('product_multi_prices', 'post')->name('post.product_multi_prices');
        Route::patch('product_multi_prices/{id}', 'patch')->name('patch.product_multi_prices');
        Route::put('product_multi_prices/{id}', 'put')->name('put.product_multi_prices');
        Route::delete('product_multi_prices/{id}', 'delete')->name('delete.product_multi_prices');
        Route::post('product_multi_prices_datatables', 'datatables')->name('datatable.product_multi_prices');
        Route::patch('product_multi_prices/{id}/approve', 'approve')->name('approve.product_multi_prices');
    });
    Route::controller(ProductHistoryController::class)->group(function() {
        Route::get('product_histories/{id?}', 'get')->name('get.product_histories');
        Route::post('product_histories', 'post')->name('post.product_histories');
        Route::patch('product_histories/{id}', 'patch')->name('patch.product_histories');
        Route::put('product_histories/{id}', 'put')->name('put.product_histories');
        Route::delete('product_histories/{id}', 'delete')->name('delete.product_histories');
        Route::post('product_histories_datatables', 'datatables')->name('datatable.product_histories');
        Route::patch('product_histories/{id}/approve', 'approve')->name('approve.product_histories');
    });
    Route::controller(ProductClosingController::class)->group(function() {
        Route::get('product_closings/{id?}', 'get')->name('get.product_closings');
        Route::post('product_closings', 'post')->name('post.product_closings');
        Route::patch('product_closings/{id}', 'patch')->name('patch.product_closings');
        Route::put('product_closings/{id}', 'put')->name('put.product_closings');
        Route::delete('product_closings/{id}', 'delete')->name('delete.product_closings');
        Route::post('product_closings_datatables', 'datatables')->name('datatable.product_closings');
        Route::patch('product_closings/{id}/approve', 'approve')->name('approve.product_closings');
    });
    Route::controller(PointHistoryController::class)->group(function() {
        Route::get('point_histories/{id?}', 'get')->name('get.point_histories');
        Route::post('point_histories', 'post')->name('post.point_histories');
        Route::patch('point_histories/{id}', 'patch')->name('patch.point_histories');
        Route::put('point_histories/{id}', 'put')->name('put.point_histories');
        Route::delete('point_histories/{id}', 'delete')->name('delete.point_histories');
        Route::post('point_histories_datatables', 'datatables')->name('datatable.point_histories');
        Route::patch('point_histories/{id}/approve', 'approve')->name('approve.point_histories');
    });
    Route::controller(AccountingJournalController::class)->group(function() {
        Route::get('accounting_journals/{id?}', 'get')->name('get.accounting_journals');
        Route::post('accounting_journals', 'post')->name('post.accounting_journals');
        Route::patch('accounting_journals/{id}', 'patch')->name('patch.accounting_journals');
        Route::put('accounting_journals/{id}', 'put')->name('put.accounting_journals');
        Route::delete('accounting_journals/{id}', 'delete')->name('delete.accounting_journals');
        Route::post('accounting_journals_datatables', 'datatables')->name('datatable.accounting_journals');
        Route::patch('accounting_journals/{id}/approve', 'approve')->name('approve.accounting_journals');
    });
    Route::controller(SalesInvoiceController::class)->group(function() {
        Route::get('sales_invoices/{id?}', 'get')->name('get.sales_invoices');
        Route::post('sales_invoices', 'post')->name('post.sales_invoices');
        Route::patch('sales_invoices/{id}', 'patch')->name('patch.sales_invoices');
        Route::put('sales_invoices/{id}', 'put')->name('put.sales_invoices');
        Route::delete('sales_invoices/{id}', 'delete')->name('delete.sales_invoices');
        Route::post('sales_invoices_datatables', 'datatables')->name('datatable.sales_invoices');
        Route::patch('sales_invoices/{id}/approve', 'approve')->name('approve.sales_invoices');
    });
    Route::controller(SalesInvoiceDetailController::class)->group(function() {
        Route::get('sales_invoice_details/{id?}', 'get')->name('get.sales_invoice_details');
        Route::post('sales_invoice_details', 'post')->name('post.sales_invoice_details');
        Route::patch('sales_invoice_details/{id}', 'patch')->name('patch.sales_invoice_details');
        Route::put('sales_invoice_details/{id}', 'put')->name('put.sales_invoice_details');
        Route::delete('sales_invoice_details/{id}', 'delete')->name('delete.sales_invoice_details');
        Route::post('sales_invoice_details_datatables', 'datatables')->name('datatable.sales_invoice_details');
        Route::patch('sales_invoice_details/{id}/approve', 'approve')->name('approve.sales_invoice_details');
    });
    Route::controller(SalesInvoiceDetailVariantController::class)->group(function() {
        Route::get('sales_invoice_detail_variants/{id?}', 'get')->name('get.sales_invoice_detail_variants');
        Route::post('sales_invoice_detail_variants', 'post')->name('post.sales_invoice_detail_variants');
        Route::patch('sales_invoice_detail_variants/{id}', 'patch')->name('patch.sales_invoice_detail_variants');
        Route::put('sales_invoice_detail_variants/{id}', 'put')->name('put.sales_invoice_detail_variants');
        Route::delete('sales_invoice_detail_variants/{id}', 'delete')->name('delete.sales_invoice_detail_variants');
        Route::post('sales_invoice_detail_variants_datatables', 'datatables')->name('datatable.sales_invoice_detail_variants');
        Route::patch('sales_invoice_detail_variants/{id}/approve', 'approve')->name('approve.sales_invoice_detail_variants');
    });
    Route::controller(WarehouseController::class)->group(function() {
        Route::get('warehouses/{id?}', 'get')->name('get.warehouses');
        Route::post('warehouses', 'post')->name('post.warehouses');
        Route::patch('warehouses/{id}', 'patch')->name('patch.warehouses');
        Route::put('warehouses/{id}', 'put')->name('put.warehouses');
        Route::delete('warehouses/{id}', 'delete')->name('delete.warehouses');
        Route::post('warehouses_datatables', 'datatables')->name('datatable.warehouses');
        Route::patch('warehouses/{id}/approve', 'approve')->name('approve.warehouses');
    });
    Route::controller(PermissionController::class)->group(function() {
        Route::get('permissions/{id?}', 'get')->name('get.permissions');
        Route::post('permissions', 'post')->name('post.permissions');
        Route::patch('permissions/{id}', 'patch')->name('patch.permissions');
        Route::put('permissions/{id}', 'put')->name('put.permissions');
        Route::delete('permissions/{id}', 'delete')->name('delete.permissions');
        Route::post('permissions_datatables', 'datatables')->name('datatable.permissions');
        Route::patch('permissions/{id}/approve', 'approve')->name('approve.permissions');
    });
    Route::controller(ModelHasPermissionController::class)->group(function() {
        Route::get('model_has_permissions/{id?}', 'get')->name('get.model_has_permissions');
        Route::post('model_has_permissions', 'post')->name('post.model_has_permissions');
        Route::patch('model_has_permissions/{id}', 'patch')->name('patch.model_has_permissions');
        Route::put('model_has_permissions/{id}', 'put')->name('put.model_has_permissions');
        Route::delete('model_has_permissions/{id}', 'delete')->name('delete.model_has_permissions');
        Route::post('model_has_permissions_datatables', 'datatables')->name('datatable.model_has_permissions');
        Route::patch('model_has_permissions/{id}/approve', 'approve')->name('approve.model_has_permissions');
    });
    Route::controller(RoleController::class)->group(function() {
        Route::get('roles/{id?}', 'get')->name('get.roles');
        Route::post('roles', 'post')->name('post.roles');
        Route::patch('roles/{id}', 'patch')->name('patch.roles');
        Route::put('roles/{id}', 'put')->name('put.roles');
        Route::delete('roles/{id}', 'delete')->name('delete.roles');
        Route::post('roles_datatables', 'datatables')->name('datatable.roles');
        Route::patch('roles/{id}/approve', 'approve')->name('approve.roles');
    });
    Route::controller(ModelHasRoleController::class)->group(function() {
        Route::get('model_has_roles/{id?}', 'get')->name('get.model_has_roles');
        Route::post('model_has_roles', 'post')->name('post.model_has_roles');
        Route::patch('model_has_roles/{id}', 'patch')->name('patch.model_has_roles');
        Route::put('model_has_roles/{id}', 'put')->name('put.model_has_roles');
        Route::delete('model_has_roles/{id}', 'delete')->name('delete.model_has_roles');
        Route::post('model_has_roles_datatables', 'datatables')->name('datatable.model_has_roles');
        Route::patch('model_has_roles/{id}/approve', 'approve')->name('approve.model_has_roles');
    });
    Route::controller(RoleHasPermissionController::class)->group(function() {
        Route::get('role_has_permissions/{id?}', 'get')->name('get.role_has_permissions');
        Route::post('role_has_permissions', 'post')->name('post.role_has_permissions');
        Route::patch('role_has_permissions/{id}', 'patch')->name('patch.role_has_permissions');
        Route::put('role_has_permissions/{id}', 'put')->name('put.role_has_permissions');
        Route::delete('role_has_permissions/{id}', 'delete')->name('delete.role_has_permissions');
        Route::post('role_has_permissions_datatables', 'datatables')->name('datatable.role_has_permissions');
        Route::patch('role_has_permissions/{id}/approve', 'approve')->name('approve.role_has_permissions');
    });
    
    Route::controller(PointOfSalesController::class)->prefix('pos')->group(function() {
        Route::post('payments', 'payment')->name('pos.payments');
        Route::get('generate_ref_numbers', 'getRefNumber')->name('pos.get_ref_number');
        Route::get('discount_point_exchange', 'discountPointExchange')->name('pos.discount_point_exchange');
        Route::post('login', 'login')->name('pos.login');
    });

});
// ---- Route Controller Generator ----
