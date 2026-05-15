<?php

namespace App\Models;

use App\Helpers\AutoNumberHelper;
use App\Helpers\GlobalHelper;
use App\Helpers\ModelHelper;
use App\Helpers\NetworkHelper;
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
use App\Http\Controllers\API\Companies\v1\TaxController;
use App\Http\Controllers\API\Companies\v1\UnitController;
use App\Http\Controllers\API\Companies\v1\VariantController;
use App\Http\Controllers\API\Companies\v1\VariantOptionController;
use App\Http\Controllers\API\Companies\v1\WarehouseController;
use App\Jobs\SyncAllJob;
use App\Models\Companies\v1\GeneralSettings;
use App\Models\Companies\v1\Permissions;
use App\Models\Companies\v1\Roles;
use App\Models\Companies\v1\Users as CompanyUsers;
use App\Models\CompanyCredentials;
use App\Models\Invoices;
use App\Models\RegRegencies;
use App\Models\SubscriptionHistories;
use App\Models\Subscriptions;
use App\Models\TransactionDetails;
use App\Models\Transactions;
use App\Models\UserCompanies;
use App\Models\Users;
use Carbon\Carbon;
use Database\Seeders\Company\DatabaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @property string user_id
 * @property string name
 * @property string address
 * @property string phone
 * @property string city
 * @property string email
 * @property string tax_id_number
 * @property string tax_id_address
 * @property int    deleted_at
 * @property int    created_at
 * @property int    updated_at
 */
class Companies extends Model
{
    use SoftDeletes;
    protected $connection = 'pgsql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies';
    

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
		'name',
		'address',
		'phone',
		'city',
		'email',
		'tax_id_number',
		'tax_id_address',
		'deleted_at',
		'created_at',
		'updated_at',
        'business_type',
        'main_project_quota',
        'main_lot_quota',
        'is_storefront',
        'domain',
        'subdomain',
        'storefront_project_quota',
        'number_of_branches',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_id' => 'string', 'name' => 'string', 'address' => 'string', 'phone' => 'string', 'city' => 'string', 'email' => 'string', 'tax_id_number' => 'string', 'tax_id_address' => 'string', 'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public $incrementing = false;

    // Scopes...

    // Functions ...

    // Relations ...
    public function subscription()
    {
        return $this->hasOne(Subscriptions::class, 'id', 'company_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'string'],
				'user_id' => ['column' => $model->table.'.user_id', 'alias' => 'user_id', 'type' => 'string'],
				'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
				'address' => ['column' => $model->table.'.address', 'alias' => 'address', 'type' => 'string'],
				'phone' => ['column' => $model->table.'.phone', 'alias' => 'phone', 'type' => 'string'],
				'city' => ['column' => $model->table.'.city', 'alias' => 'city', 'type' => 'string'],
				'email' => ['column' => $model->table.'.email', 'alias' => 'email', 'type' => 'string'],
				'tax_id_number' => ['column' => $model->table.'.tax_id_number', 'alias' => 'tax_id_number', 'type' => 'string'],
				'tax_id_address' => ['column' => $model->table.'.tax_id_address', 'alias' => 'tax_id_address', 'type' => 'string'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
                'business_type' => ['column' => $model->table.'.business_type', 'alias' => 'business_type', 'type' => 'int'],
                'main_project_quota' => ['column' => $model->table.'.main_project_quota', 'alias' => 'main_project_quota', 'type' => 'int'],
                'main_lot_quota' => ['column' => $model->table.'.main_lot_quota', 'alias' => 'main_lot_quota', 'type' => 'int'],
                'is_storefront' => ['column' => $model->table.'.is_storefront', 'alias' => 'is_storefront', 'type' => 'int'],
                'domain' => ['column' => $model->table.'.domain', 'alias' => 'domain', 'type' => 'string'],
                'subdomain' => ['column' => $model->table.'.subdomain', 'alias' => 'subdomain', 'type' => 'string'],
                'storefront_project_quota' => ['column' => $model->table.'.storefront_project_quota', 'alias' => 'storefront_project_quota', 'type' => 'int'],
                'number_of_branches' => ['column' => $model->table.'.number_of_branches', 'alias' => 'number_of_branches', 'type' => 'string'],
            ],
            'join' => [

            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $totalData = self::count();

        $qry = ModelHelper::select(self::mapSchema()['field'], null, __CLASS__);
        ModelHelper::join(self::mapSchema()['join'], null, $qry);
        
        if (count($filter) > 0) {

        }

        $totalFiltered = $qry->count();

        if (empty($search)) {
            
            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }

        } else {
            foreach (array_values(self::mapSchema()['field']) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['column'].'::varchar(255) ILIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema()['field'])) == ($key + 1)) {
                    $qry->orWhereRaw($val['column'].'::varchar(255) ILIKE \'%'.$search.'%\')');
                } else {
                    $qry->orWhereRaw($val['column'].'::varchar(255) ILIKE \'%'.$search.'%\'');
                }
            }

            $totalFiltered = $qry->count();

            if ($length > 0) {
                $qry->skip($start)
                    ->take($length);
            }

            foreach ($order as $row) {
                $qry->orderBy($row['column'], $row['dir']);
            }
        }

        return [
            'data' => $qry->get(),
            'totalData' => $totalData,
            'totalFiltered' => $totalFiltered
        ];
    }

    public static function getPaginatedResult($params, $request)
    {
        $append = [];
        $schema = self::mapSchema();

        $paramsPage = isset($params['page']) ? $params['page'] : 0;
        
        $or = [];

        unset($params['page']);

        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }

        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__);
        ModelHelper::join(self::mapSchema()['join'], $request, $db);

        if ($params) {
            ModelHelper::dynamicFilterAnd($params, $request, $db, __CLASS__);
        }

        if ($or) {
            ModelHelper::dynamicFilterOr($or, $request, $db, __CLASS__);
        }

        $results = ModelHelper::generatePagingResults($schema, $paramsPage, $params, $request, $db, $append);

        return response()->json($results);
    }

    public static function getById($id, $params = [], $request = null)
    {
        $models = new self;

        $append = [];
        
        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__)->where($models->table.'.id', $id);
        
        ModelHelper::join(self::mapSchema()['join'], $request, $db);
        
        return response()->json($db->first());
    }

    public static function getAllResult($params, $request)
    {
        $append = [];
        $schema = self::mapSchema();

        $or = [];
        
        unset($params['all']);

        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }

        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__);
        ModelHelper::join(self::mapSchema()['join'], $request, $db);

        if ($params) {
            ModelHelper::dynamicFilterAnd($params, $request, $db, __CLASS__);
        }

        if ($or) {
            ModelHelper::dynamicFilterOr($or, $request, $db, __CLASS__);
        }

        $results = ModelHelper::generateAllResults($schema, $params, $request, $db, $append);

        return response()->json($results);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::connection('pgsql')->beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $old = self::getById($params['id'])->original;

            $update = self::where('id', $params['id'])->update($params);

            DB::connection('pgsql')->commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data'
            ]);
        }

        $company_city_name = '';
        $province_id = 0;
        $city_id = $params['company_city'];

        $user = Users::where('email', $params['email'])->first();
        $city = RegRegencies::select('id', 'province_id', 'name')->where('id', $city_id)->first();

        if ($city) {
            $company_city_name = ucwords(strtolower($city['name']));
            $province_id = $city['province_id'];
        }

        $company_id = Str::orderedUuid()->toString();
        $company['id'] = $company_id;
        $company['user_id'] = $user['id'];
        $company['city'] = $company_city_name;
        $company['name'] = $params['company_name'];

        //Create Company
        self::create($company);

        //Point User Have Company
        UserCompanies::create([
            'id' => Str::orderedUuid()->toString(),
            'user_id' => $company['user_id'],
            'company_id' => $company['id'],
            'type' => 'member'
        ]);

        $current_timestamp = date('Y-m-d H:i:s');
        $default_trial_timestamp = date('Y-m-d H:i:s', strtotime($current_timestamp. ' + '.config('default_trial_day').' days'));
        $subscription_id = Str::orderedUuid()->toString();
        //Create Subscription
        Subscriptions::create([
            'id' => $subscription_id,
            'company_id' => $company_id,
            'start_at' => $current_timestamp,
            'finish_at' => $default_trial_timestamp,
            'status' => 'Trial',
            'referral_code' => isset($params['referral_code']) && $params['referral_code'] ? $params['referral_code'] : null,
        ]);

        //Create History Subscription
        SubscriptionHistories::create([
            'id' => Str::orderedUuid()->toString(),
            'subscription_id' => $subscription_id,
            'company_id' => $company_id,
            'start_at' => $current_timestamp,
            'finish_at' => $default_trial_timestamp,
            'status' => 'Trial',
            'type' => 'trial',
            'referral_code' => isset($params['referral_code']) && $params['referral_code'] ? $params['referral_code'] : null,
        ]);

        $pattern = '/([^a-z0-9]+)/';
        $slug = preg_replace($pattern,'', strtolower('mdp-'.$params['company_name'])) . date('ynjGis');

        //Create Database
        $check_db = DB::select("SELECT 1 FROM pg_catalog.pg_database WHERE datname = '{$slug}'");
        if (count($check_db) > 0) {
            DB::connection('pgsql')->Rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Database is exist',
                'data' => null
            ]);
        }

        //Create Company Credentials
        CompanyCredentials::create([
            'id' => Str::orderedUuid()->toString(),
            'company_id' => $company_id,
            'db_driver' => config('default_db_driver'),
            'db_host' => config('default_db_host'),
            'db_username' => config('default_db_user'),
            'db_password' => config('default_db_password'),
            'db_database' => $slug,
            'db_port' => config('default_db_port')
        ]);

        $general_settings = GeneralSettings::defaultGeneralSettings([
            'company_name' => $company['name'],
            'director_name' => $user['name'],
            'province_id' => $province_id,
            'city_id' => $city_id,
        ]);

        DB::connection('pgsql')->commit();

        DB::connection('pgsql')->statement("CREATE DATABASE {$slug}");

        config(['database.connections.pgsql_companies' => [
            'driver' => 'pgsql',
            'host' => config('default_db_host'),
            'port' => config('default_db_port'),
            'database' => $slug,
            'username' => config('default_db_user'),
            'password' => config('default_db_password'),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ]]);

        Artisan::call('migrate', ['--path' => 'database/migration_company', '--database' => 'pgsql_companies']);
        Artisan::call('migrate', ['--path' => 'database/migration_company_alter', '--database' => 'pgsql_companies']);
        ModelHelper::adjustSequencePostgreSql();
        Artisan::call('db:seed', ['--class' => DatabaseSeeder::class]);
        Artisan::call('permission:cache-reset');
        Artisan::call('migrate', ['--path' => 'database/migration_company_after_seed', '--database' => 'pgsql_companies']);
        if (Artisan::output()) {
            ModelHelper::reorderPermissionAdmin();
            GeneralSettings::truncate();
            DB::connection('pgsql_companies')->statement("SELECT SETVAL('general_settings_id_seq', (SELECT MAX(id) + 1 FROM general_settings))");

            GeneralSettings::insert($general_settings);

            $users['password'] = '123456';
            $users['name'] = $user['name'];
            $users['email'] = $user['email'];
            $users['username'] = $user['email'];
            $users['phone'] = $user['phone'];
            if (!isset($user['phone']) || (isset($user['phone']) && !$user['phone'])) {
                $users['phone'] = GlobalHelper::randomText('numeric', 11);
            }
            $users['role_id'] = 1;
            $users['cluster_id'] = 0;
            $users['cluster_ids'] = [0];
            $users['is_from_registration'] = true;
            $users['employee_id'] = 1;
            CompanyUsers::createOrUpdate($users, 'POST', $request);

            ModelHelper::adjustSequencePostgreSql();
        } 

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($company['id'])->original
        ]);
    }

    public static function deleteById($id, $params, $request)
    {
        DB::connection('pgsql')->beginTransaction();

        $old = self::where('id', $id)->first();

        if ($old['user_id'] != config('user.id')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak dapat menghapus data ini, karena Anda adalah bukan pembuat dari data perusahaan ini',
                'data' => null
            ], 400);
        }

        self::where('id', $id)->delete();
        CompanyCredentials::where('company_id', $id)->delete();
        SubscriptionHistories::where('company_id', $id)->delete();
        Subscriptions::where('company_id', $id)->delete();
        Transactions::where('company_id', $id)->delete();
        UserCompanies::where('company_id', $id)->delete();

        DB::connection('pgsql')->commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data',
            'data' => null
        ]);
    }

    public static function activateCompany($company_id, $params)
    {
        DB::connection('pgsql')->beginTransaction();

        $subscription_type = 'subscribe';
        $total = 0;

        $company = self::where('id', $company_id)->first();
        $subscription = Subscriptions::where('company_id', $company_id)->first();

        if ($subscription['status'] == 'Trial' || $subscription['status'] == 'Suspend') {
            if ($params['type'] == 'ios_in_apps') {
                if ($params['product_sku'] == '1_year_mandep_starter') {
                    $total = 18900000;
                    $start_date = date('Y-m-d H:i:s');
                    $expired_date = Carbon::parse($start_date)->addYear()->format('Y-m-d H:i:s');
                }
            }
        } else if ($subscription['status'] == 'Subscribe') {
            $subscription_type = 'renewal';
            if ($params['type'] == 'ios_in_apps') {
                if ($params['product_sku'] == '1_year_mandep_starter') {
                    $total = 18900000;
                    $start_date = $subscription['finish_at'];
                    $expired_date = Carbon::parse($start_date)->addYear()->format('Y-m-d H:i:s');
                }
            }
        } else if ($subscription['status'] == 'Not Active') {
            return [
                'status' => 'error',
                'message' => 'Status perusahaan Anda telah Not Active, Data Anda telah dihapus dari database kami.',
                'data' => null
            ];
        }

        $subscription->start_at = $start_date;
        $subscription->finish_at = $expired_date;
        $subscription->status = 'Subscribe';
        $subscription->update();

        SubscriptionHistories::create([
            'id' => Str::orderedUuid()->toString(),
            'subscription_id' => $subscription['id'],
            'company_id' => $company_id,
            'start_at' => $start_date,
            'finish_at' => $expired_date,
            'status' => 'Subscribe',
            'referral_code' => '',
            'type' => $subscription_type,
        ]);

        $data_transaction['id'] = Str::orderedUuid()->toString();
        $data_transaction['company_id'] = $company_id;
        $data_transaction['number'] = AutoNumberHelper::initGenerateNumber('TR-');
        $data_transaction['date'] = date('Y-m-d H:i:s');
        $data_transaction['discount_amount'] = 0;
        $data_transaction['discount_percentage'] = 0;
        $data_transaction['referral_code'] = '';
        $data_transaction['voucher_code'] = '';
        $data_transaction['total'] = $total;
        $data_transaction['note'] = 'Biaya Berlangganan IOS In Apps '.$company['name'];

        $save_transaction = Transactions::create($data_transaction);

        if ($save_transaction) {
            $transaction_detail['id'] = Str::orderedUuid()->toString();
            $transaction_detail['transaction_id'] = $data_transaction['id'];
            $transaction_detail['product_name'] = $params['product_sku'];
            $transaction_detail['qty'] = 1;
            $transaction_detail['unit_price'] = $total;
            $transaction_detail['discount_amount'] = 0;
            $transaction_detail['discount_percentage'] = 0;

            TransactionDetails::create($transaction_detail);

            $invoice['id'] = Str::orderedUuid()->toString();
            $invoice['number'] = AutoNumberHelper::initGenerateNumber('INV-');
            $invoice['transactions_id'] = $data_transaction['id'];
            $invoice['total'] = $total;
            $invoice['callback_url'] = '';
            $invoice['payment_code'] = $params['type'];
            $invoice['payment_via'] = $params['type'];

            Invoices::create($invoice);
        }

        DB::connection('pgsql')->commit();

        return [
            'status' => 'success',
            'message' => 'Company succesfully activated',
            'data' => null
        ];
    }

    public static function databaseStarter($params, $request)
    {   

        if (!config('services.is_onpremise')) {
            return;
        }

        if (!NetworkHelper::isConnected()) {

            DB::connection('pgsql')->beginTransaction();

            try {

                $company_payload = $params['company'] ?? null;
                $subscription_payload = $params['subscription'] ?? null;
                $slug = null;
    
                if (!$company_payload || !$subscription_payload) {
                    DB::connection('pgsql')->rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Invalid payload: company or subscription missing'
                    ], 400);
                }

                $slug = $params['slug'];

                $check_db = DB::connection('pgsql_admin')->select("SELECT 1 FROM pg_catalog.pg_database WHERE datname = ?", [$slug]);

                if (empty($check_db)) {
                    DB::connection('pgsql_admin')->rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Database tidak ditemukan, Harap sinkron ulang'
                    ], 400);
                }

                config(['database.connections.pgsql_companies' => [
                    'driver' => 'pgsql',
                    'host' => config('database.connections.pgsql.host'),
                    'port' => config('database.connections.pgsql.port'),
                    'database' => $slug,
                    'username' => config('database.connections.pgsql.username'),
                    'password' => config('database.connections.pgsql.password'),
                    'charset' => 'utf8',
                    'prefix' => '',
                    'prefix_indexes' => true,
                    'schema' => 'public',
                    'sslmode' => 'prefer',
                ]]);

                DB::purge('pgsql_companies');
                DB::reconnect('pgsql_companies');

                $request->session()->put('_company_id', $params['company_id']);

                $result = Artisan::call('migrate', [ '--path' => 'database/migration_company', '--database' => 'pgsql_companies', '--force' => true]);

                if ($result != 0) {
                    DB::connection('pgsql_companies')->rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Migration failed: ' . Artisan::output()
                    ], 500);
                }

                if ($result == 0) {
                    $user = Users::find($company_payload['user_id']);

                    if ($user) {
                        $company_user = CompanyUsers::where('email', $user->email)->first();

                        if (!$company_user) {
                            DB::connection('pgsql_companies')->rollBack();
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Pengguna tidak ditemukan. Harap tambahkan pengguna di <a href="https://app.abcerp.id">https://app.abcerp.id</a>'
                            ], 400);
                        }
                    } else {
                        DB::connection('pgsql_companies')->rollBack();
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Pengguna tidak ditemukan di database central'
                        ], 400);
                    }
                }
            
            } catch (\Throwable $th) {

                DB::connection('pgsql')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $th->getMessage()
                ]);
            }
        }

        DB::connection('pgsql')->beginTransaction();

        try {
            $company_payload = $params['company'] ?? null;
            $subscription_payload = $params['subscription'] ?? null;
            $slug = null;

            if (!$company_payload || !$subscription_payload) {
                DB::connection('pgsql')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid payload: company or subscription missing'
                ], 400);
            }

            $company = Companies::join('company_credentials', 'company_credentials.company_id', '=', 'companies.id')
                ->where('companies.id', $params['company_id'])
                ->select('companies.*', 'company_credentials.*')
                ->first();

            /**
             * =====================================
             * CREATE COMPANY
             * =====================================
             */
            if (!$company) {
                $company = Companies::create([
                    'id' => $company_payload['id'],
                    'user_id' => $company_payload['user_id'],
                    'name' => $company_payload['name'],
                    'address' => $company_payload['address'],
                    'phone' => $company_payload['phone'],
                    'city' => $company_payload['city'],
                    'email' => $company_payload['email'],
                    'tax_id_number' => $company_payload['tax_id_number'],
                    'tax_id_address' => $company_payload['tax_id_address'],
                    'business_type' => $company_payload['business_type'],
                    'main_project_quota' => $company_payload['main_project_quota'],
                    'main_lot_quota' => $company_payload['main_lot_quota'],
                    'is_storefront' => $company_payload['is_storefront'],
                    'domain' => $company_payload['domain'],
                    'subdomain' => $company_payload['subdomain'],
                    'storefront_project_quota' => $company_payload['storefront_project_quota'],
                    'number_of_branches' => $company_payload['number_of_branches'],
                ]);

                /**
                 * =====================================
                 * USER COMPANY RELATION
                 * =====================================
                 */
                UserCompanies::create([
                    'id' => $params['id'],
                    'user_id' => $params['user_id'],
                    'company_id' => $params['company_id'],
                    'type' => strtolower($params['type'] ?? 'member'),
                ]);

                /**
                 * =====================================
                 * SUBSCRIPTION
                 * =====================================
                 */
                Subscriptions::create([
                    'id' => $subscription_payload['id'],
                    'company_id' => $subscription_payload['company_id'],
                    'start_at' => $subscription_payload['start_at'],
                    'finish_at' => $subscription_payload['finish_at'],
                    'status' => strtolower($subscription_payload['status']),
                    'referral_code' => $subscription_payload['referral_code'],
                ]);

                /**
                 * =====================================
                 * SUBSCRIPTION HISTORY
                 * =====================================
                 */
                SubscriptionHistories::create([
                    'id' => Str::orderedUuid()->toString(),
                    'subscription_id' => $subscription_payload['id'],
                    'company_id' => $subscription_payload['company_id'],
                    'start_at' => $subscription_payload['start_at'],
                    'finish_at' => $subscription_payload['finish_at'],
                    'status' => strtolower($subscription_payload['status']),
                    'type' => strtolower($subscription_payload['status']),
                    'referral_code' => $subscription_payload['referral_code'],
                ]);

                /**
                 * =====================================
                 * DATABASE SLUG SAFE
                 * =====================================
                 */
                $slug = $params['slug'];

                /**
                 * CHECK DB EXISTS
                 */
                $check_db = DB::connection('pgsql_admin')->select("SELECT 1 FROM pg_catalog.pg_database WHERE datname = ?", [$slug]);

                if (!empty($check_db)) {
                    DB::connection('pgsql_admin')->rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Database already exists'
                    ], 400);
                }

                /**
                 * SAVE CREDENTIALS
                 */
                CompanyCredentials::create([
                    'id' => Str::orderedUuid()->toString(),
                    'company_id' => $params['company_id'],
                    'db_driver' => config('database.connections.pgsql.driver'),
                    'db_host' => config('database.connections.pgsql.host'),
                    'db_username' => config('database.connections.pgsql.username'),
                    'db_password' => config('database.connections.pgsql.password'),
                    'db_database' => $slug,
                    'db_port' => config('database.connections.pgsql.port')
                ]);

                DB::connection('pgsql')->commit();
                /**
                 * CREATE DATABASE
                 */
                DB::connection('pgsql_admin')->statement("CREATE DATABASE \"{$slug}\"");
            }

            /**
             * CONFIG CONNECTION
             */
            config(['database.connections.pgsql_companies' => [
                'driver' => 'pgsql',
                'host' => config('database.connections.pgsql.host'),
                'port' => config('database.connections.pgsql.port'),
                'database' => $slug ?: $company->db_database,
                'username' => config('database.connections.pgsql.username'),
                'password' => config('database.connections.pgsql.password'),
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'schema' => 'public',
                'sslmode' => 'prefer',
            ]]);

            DB::purge('pgsql_companies');
            DB::reconnect('pgsql_companies');
            
            /**
             * =====================================
             * RUN MIGRATION
             * =====================================
             */
            $request->session()->put('_company_id', $params['company_id']);

            $result = Artisan::call('migrate', [ '--path' => 'database/migration_company', '--database' => 'pgsql_companies', '--force' => true]);

            if ($result != 0) {
                DB::connection('pgsql_companies')->rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Migration failed: ' . Artisan::output()
                ], 500);
            }

            if ($result == 0) {
                $user = Users::find($company_payload['user_id']);

                if ($user) {
                    $company_user = CompanyUsers::where('email', $user->email)->first();

                    if (!$company_user) {
                        $phone = $user->phone;
    
                        if (!$phone) {
                            $phone = GlobalHelper::randomText('numeric', 11);
                        }
    
                        $users = [];
                        $users['password'] = $user->password;
                        $users['name'] = $user->name;
                        $users['email'] = $user->email;
                        $users['username'] = $user->email;
                        $users['phone'] = $phone;
                        $users['is_suspend'] = 0;
                        $users['role_id'] = 1;
                        $users['department_id'] = 0;
                        $users['branch_ids'] = [0];
                        $users['project_ids'] = [0];
                        $users['warehouse_ids'] = [0];
                        $users['employee_id'] = 1;
                        $users['contact_id'] = 0;
                        $users['is_from_registration'] = true;
    
                        CompanyUsers::createOrUpdate($users, 'POST', $request);

                        Roles::create([
                            'name' => 'SuperAdmin',
                            'guard_name' => 'web',
                        ]);

                        Permissions::create([
                            'name' => 'pos',
                            'guard_name' => 'web',
                        ]);

                        Artisan::call('permission:cache-reset');
                    }
                }

                ModelHelper::reorderPermissionAdmin();
                ModelHelper::adjustSequencePostgreSql();
            }
        } catch (\Exception $e) {
            DB::connection('pgsql')->rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
