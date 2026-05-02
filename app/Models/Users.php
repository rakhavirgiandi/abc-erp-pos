<?php

namespace App\Models;

use App\Helpers\EmailSmtpService;
use App\Helpers\GlobalHelper;
use App\Helpers\ModelHelper;
use App\Helpers\NetworkHelper;
use App\Models\Companies;
use App\Models\Companies\v1\GeneralSettings;
use App\Models\Companies\v1\Users as CompanyUsers;
use App\Models\PasswordResets;
use App\Models\RegRegencies;
use App\Models\SubscriptionHistories;
use App\Models\Subscriptions;
use App\Models\UserCompanies;
use Auth;
use Carbon\Carbon;
use Database\Seeders\Company\DatabaseSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/**
 * @property string name
 * @property string email
 * @property string phone
 * @property string type
 * @property string community
 * @property string password
 * @property string remember_token
 * @property int    email_verified_at
 * @property int    deleted_at
 * @property int    created_at
 * @property int    updated_at
 */
class Users extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'pgsql';

    protected $table = 'users';
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
        'name',
		'email',
		'phone',
		'type',
		'email_verified_at',
		'password',
		'remember_token',
		'deleted_at',
		'created_at',
		'updated_at',
        'association_id',
        'is_hold'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name' => 'string', 'email' => 'string', 'phone' => 'string', 'type' => 'string', 'community' => 'string', 'email_verified_at' => 'datetime', 'password' => 'string', 'remember_token' => 'string', 'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'email_verified_at', 'deleted_at', 'created_at', 'updated_at'
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
    public function userCompanies()
    {
        return $this->hasMany(UserCompanies::class, 'user_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'string'],
				'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
				'email' => ['column' => $model->table.'.email', 'alias' => 'email', 'type' => 'string'],
				'phone' => ['column' => $model->table.'.phone', 'alias' => 'phone', 'type' => 'string'],
				'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'string'],
				'email_verified_at' => ['column' => $model->table.'.email_verified_at', 'alias' => 'email_verified_at', 'type' => 'date'],
				'password' => ['column' => $model->table.'.password', 'alias' => 'password', 'type' => 'string'],
				'remember_token' => ['column' => $model->table.'.remember_token', 'alias' => 'remember_token', 'type' => 'string'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
                'association_id' => ['column' => $model->table.'.association_id', 'alias' => 'association_id', 'type' => 'int'],
                'is_hold' => ['column' => $model->table.'.is_hold', 'alias' => 'is_hold', 'type' => 'int'],
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

    public static function getById($id, $params = [], $request = null, $type = 'member')
    {
        $models = new self;

        $append = [];
        
        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__)->where($models->table.'.id', $id);
        
        ModelHelper::join(self::mapSchema()['join'], $request, $db);
        
        $db->with(['userCompanies' => function($q) use($type) {
            $q->where('type', $type)->with('company');
        }]);

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
        $pattern = '/([^a-z0-9]+)/';
        $slug = preg_replace($pattern,'', strtolower('erp-'.$params['company']['name'])) . date('ynjGis');

        DB::connection('pgsql')->beginTransaction();

        $filename = null;
        $company = [];

        $messages = [
            'company.number_of_branches.numeric' => 'Harap Masukkan Berupa Angka!'
        ];

        $rules = [
            'company.number_of_branches' => 'required|numeric'
        ];

        $validator = Validator::make($params, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'data' => $validator->errors()->all(),
            ], 422);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['company']) && $params['company']) {
            $company = $params['company'];
            unset($params['company']);
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

        if (self::where('email', $params['email'])->count() > 0) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email anda telah terdaftar sebelumnya. Silahkan Login atau klik Lupa Password',
                'message_html' => 'Email anda telah terdaftar sebelumnya. Silahkan <a href="'.url('/login').'">Login</a> atau Klik <a href="'.url('/forgot-password').'">Lupa Password</a>',
                'data' => null
            ]);
        }

        $id = Str::orderedUuid()->toString();
        $params['id'] = $id;
        $unencrypt_password = $params['password'];
        $params['password'] = bcrypt($params['password']);

        $params['is_hold'] = true;

        $company_city_name = '';
        $province_id = 0;
        $city_id = $company['city'];

        $city = RegRegencies::select('id', 'province_id', 'name')->where('id', $city_id)->first();

        if ($city) {
            $company_city_name = ucwords(strtolower($city['name']));
            $province_id = $city['province_id'];
        }

        $save = self::create($params);

        if ($save) {
            $company_id = Str::orderedUuid()->toString();
            $company['id'] = $company_id;
            $company['user_id'] = $id;

            //Create Company
            Companies::create($company);

            //Point User Have Company
            UserCompanies::create([
                'id' => Str::orderedUuid()->toString(),
                'user_id' => $id,
                'company_id' => $company_id,
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
        }

        $general_settings = GeneralSettings::defaultGeneralSettings([
            'company_name' => $company['name'],
            'director_name' => $params['name'],
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

            $users['password'] = $unencrypt_password;
            $users['name'] = $params['name'];
            $users['email'] = $params['email'];
            $users['username'] = $params['email'];
            if (!isset($params['phone']) || (isset($params['phone']) && !$params['phone'])) {
                $params['phone'] = GlobalHelper::randomText('numeric', 11);
            }
            $users['phone'] = $params['phone'];
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
       
            ModelHelper::adjustSequencePostgreSql();
        } 

        $send_email = new EmailSmtpService();
        $send_email->composeEmail([
            'type' => 'registrations',
            'email' => $params['email'],
            'name' => $params['name'],
            'code' => '999',
            'password' => $unencrypt_password
        ]);

        // DB::connection('pgsql_companies')->statement('alter table "financial_submission_details" add constraint "financial_submission_details_financial_submission_id_foreign" foreign key ("financial_submission_id") references "financial_submissions" ("id") on delete CASCADE');

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($save->id)->original
        ]);
    }

    public static function generateToken($params, $method, $request, $type = 'member')
    {
        if (env('IS_ONPREMISE', false)) {
            if (NetworkHelper::isConnected()) {
                $db = env('DB_DATABASE');
    
                $exists = DB::connection('pgsql_admin')->select("SELECT 1 FROM pg_database WHERE datname = ?", [$db]);
    
                if (empty($exists)) {
                    DB::connection('pgsql_admin')->statement("CREATE DATABASE \"{$db}\"");
                }
    
                DB::purge('pgsql');
                DB::reconnect('pgsql');
    
                Artisan::call('migrate', [ '--database' => 'pgsql', '--force' => true ]);

                $clientExists = DB::connection('pgsql')->table('oauth_clients')->where('personal_access_client', true)->exists();

                if (!$clientExists) {
                    $clientId = Str::uuid()->toString();
                    $clientSecret = Str::random(40);

                    DB::connection('pgsql')->table('oauth_clients')->insert([
                        'id' => $clientId,
                        'user_id' => null,
                        'name' => 'Personal Access Client',
                        'secret' => hash('sha256', $clientSecret),
                        'provider' => 'users',
                        'redirect' => 'http://localhost',
                        'personal_access_client' => true,
                        'password_client' => false,
                        'revoked' => false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::connection('pgsql')->table('oauth_personal_access_clients')->insert([
                        'client_id' => $clientId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $response = null;

                try {
                    $response = NetworkHelper::loginToServer($params);
                } catch (\Throwable $e) {
                    \Log::warning('Login server gagal: ' . $e->getMessage());
                
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Username dan Password Tidak Sesuai',
                        'data' => null  
                    ], 401);
                }

                if ($response) {
                    $data = $response['data'];

                    $user = self::where('email', $data['email'])->first();

                    if ($user) {
                        $user->update([
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'phone' => $data['phone'],
                            'type' => $data['type'],
                            'password' => bcrypt($params['password']),
                            'email_verified_at' => $data['email_verified_at'],
                            'remember_token' => $data['remember_token'],
                            'deleted_at' => $data['deleted_at'],
                            'association_id' => $data['association_id'],
                            'is_hold' => $data['is_hold'],
                            'created_at' => $data['created_at'],
                            'updated_at' => $data['updated_at'],
                        ]);
                    } else {
                        $user = self::create([
                            'id' => $data['id'],
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'phone' => $data['phone'],
                            'type' => $data['type'],
                            'password' => bcrypt($params['password']),
                            'email_verified_at' => $data['email_verified_at'],
                            'remember_token' => $data['remember_token'],
                            'deleted_at' => $data['deleted_at'],
                            'association_id' => $data['association_id'],
                            'is_hold' => $data['is_hold'],
                            'created_at' => $data['created_at'],
                            'updated_at' => $data['updated_at'],
                        ]);
                    }
                }
            }
        }
        
        $user_key = 'email';
        $user_value = '';

        if (isset($params['email'])) {
            $user_key = 'email';
            $user_value = $params['email'];
        }

        $credentials = request([$user_key, 'password']);

        if(!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Username dan Password Tidak Sesuai',
                'data' => null
            ], 401);
        }

        $user = $request->user();

        $get_user_detail = self::getById($user->id, [], null, $type);
        $get_user_detail = $get_user_detail->getData();

        if (!isset($get_user_detail->id)) {
            $get_user_detail = null;
        } else {
            // if (isset($params['fcm_token'])) {
            //     self::where('id', $get_user_detail->id)->update([
            //         'fcm_token' => $params['fcm_token']
            //     ]); 
            // }
        }

        $tokenResult = $user->createToken('login_member_'.$user_value);

        $token = $tokenResult->token;

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            'data' => $get_user_detail
        ]);
    }

    public static function deleteById($id, $params, $request)
    {
        self::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data'
        ]);
    }

    public static function forgotPassword($params)
    {
        $user = self::where('email', $params['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak diketahui'
            ], 400);            
        }

        $token = GlobalHelper::randomText('alnum', 50);

        PasswordResets::where('email', $params['email'])->delete();

        PasswordResets::create([
            'token' => $token,
            'email' => $params['email'],
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $send_email = new EmailSmtpService();
        $send_email->composeEmail([
            'type' => 'forgot_password',
            'email' => $params['email'],
            'name' => $user['name'],
            'code' => $token,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Silahkan cek Email anda pada Kotak Masuk ataupun Spam!'
        ]);
    }

    public static function newPassword($params)
    {
        DB::connection('pgsql')->beginTransaction();

        if ($params['password'] != $params['confirm_password']) {
            return response()->json([
                'status' => 'error',
                'message' => 'Password baru dan konfirmasi password baru tidak sama!'
            ], 400);
        }
        
        $now_date = Carbon::now();

        $password_token = PasswordResets::where('token', $params['code'])->first();

        if (!$password_token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token tidak sesuai! Silahkan Lupa Password kembali dan pastikan email yang anda dapat adalah yang terbaru'
            ], 400);
        }

        if (!$now_date->gt($password_token->created_at->addDays(1))) {
            $user = self::where('email', $password_token['email'])->first();

            $update = self::where('email', $password_token->email)->update([
                'password' => bcrypt($params['password'])
            ]);

            if ($user && $update) {
                PasswordResets::where('token', $params['code'])->delete();
            }

            DB::connection('pgsql')->commit();

            $send_email = new EmailSmtpService();
            $send_email->composeEmail([
                'type' => 'new_passwords',
                'email' => $password_token->email,
                'name' => $user['name'],
                'password' => $params['password']
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sukses merubah password, silahkan login'
            ]);
        } else {
            DB::connection('pgsql')->Rollback();
            return response()->json([
                'status' => 'error',
                'message' => 'Token sudah kadaluarsa'
            ], 400);
        }
    }

    public static function deleteRuquest($params)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unknown Email and Password'
            ], 401);
        }

        self::where('email', $params['email'])->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Users',
            'data' => null
        ]);
    }
}
