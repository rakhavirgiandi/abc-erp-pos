<?php

namespace App\Models\Companies\v1;

use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property string name
 * @property string email
 * @property string password
 * @property string fcm_token
 * @property string username
 * @property string branch_ids
 * @property string project_ids
 * @property string warehouse_ids
 * @property string remember_token
 * @property int    email_verified_at
 * @property int    is_suspend
 * @property int    role_id
 * @property int    department_id
 * @property int    branch_id
 * @property int    employee_id
 * @property int    contact_id
 * @property int    warehouse_id
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class Users extends Authenticatable
{
    use SoftDeletes, HasRoles, HasApiTokens;
    
    protected $connection = 'pgsql_companies';
    
    protected $guard_name = 'web';
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'email',
		'email_verified_at',
		'password',
		'fcm_token',
		'username',
		'is_suspend',
		'role_id',
		'department_id',
		'branch_id',
		'employee_id',
		'branch_ids',
		'project_ids',
		'warehouse_ids',
		'contact_id',
		'warehouse_id',
		'remember_token',
		'created_at',
		'updated_at',
		'deleted_at',
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
        'name' => 'string', 'email' => 'string', 'email_verified_at' => 'datetime', 'password' => 'string', 'fcm_token' => 'string', 'username' => 'string', 'is_suspend' => 'int', 'role_id' => 'int', 'department_id' => 'int', 'branch_id' => 'int', 'employee_id' => 'int', 'branch_ids' => 'string', 'project_ids' => 'string', 'warehouse_ids' => 'string', 'contact_id' => 'int', 'warehouse_id' => 'int', 'remember_token' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [

    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public $incrementing = true;

    // Scopes...

    // Functions ...

    // Relations ...

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
				'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
				'email' => ['column' => $model->table.'.email', 'alias' => 'email', 'type' => 'string'],
				'email_verified_at' => ['column' => $model->table.'.email_verified_at', 'alias' => 'email_verified_at', 'type' => 'date'],
				'password' => ['column' => $model->table.'.password', 'alias' => 'password', 'type' => 'string'],
				'fcm_token' => ['column' => $model->table.'.fcm_token', 'alias' => 'fcm_token', 'type' => 'string'],
				'username' => ['column' => $model->table.'.username', 'alias' => 'username', 'type' => 'string'],
				'is_suspend' => ['column' => $model->table.'.is_suspend', 'alias' => 'is_suspend', 'type' => 'int'],
				'role_id' => ['column' => $model->table.'.role_id', 'alias' => 'role_id', 'type' => 'int'],
				'department_id' => ['column' => $model->table.'.department_id', 'alias' => 'department_id', 'type' => 'int'],
				'branch_id' => ['column' => $model->table.'.branch_id', 'alias' => 'branch_id', 'type' => 'int'],
				'employee_id' => ['column' => $model->table.'.employee_id', 'alias' => 'employee_id', 'type' => 'int'],
				'branch_ids' => ['column' => $model->table.'.branch_ids', 'alias' => 'branch_ids', 'type' => 'string'],
				'project_ids' => ['column' => $model->table.'.project_ids', 'alias' => 'project_ids', 'type' => 'string'],
				'warehouse_ids' => ['column' => $model->table.'.warehouse_ids', 'alias' => 'warehouse_ids', 'type' => 'string'],
				'contact_id' => ['column' => $model->table.'.contact_id', 'alias' => 'contact_id', 'type' => 'int'],
				'warehouse_id' => ['column' => $model->table.'.warehouse_id', 'alias' => 'warehouse_id', 'type' => 'int'],
				'remember_token' => ['column' => $model->table.'.remember_token', 'alias' => 'remember_token', 'type' => 'string'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
            ],
            'join' => [

            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $schema = self::mapSchema();

        $totalData = self::count();

        $qry = ModelHelper::select($schema['field'], null, __CLASS__);
        ModelHelper::join($schema['join'], null, $qry);
        
        //FILTER

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
            foreach (array_values($schema['field']) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['column'].'::varchar(255) ILIKE \'%'.$search.'%\'');
                } else if (count(array_values($schema['field'])) == ($key + 1)) {
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

        $db = ModelHelper::select($schema['field'], $request, __CLASS__);
        ModelHelper::join($schema['join'], $request, $db);

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

        $schema = self::mapSchema();
        
        $db = ModelHelper::select($schema['field'], $request, __CLASS__)->where($models->table.'.id', $id);
        
        ModelHelper::join($schema['join'], $request, $db);
        
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

        $db = ModelHelper::select($schema['field'], $request, __CLASS__);
        ModelHelper::join($schema['join'], $request, $db);

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
        DB::connection('pgsql_companies')->beginTransaction();

        $filename = null;
        $filepath = null;

        if (isset($params['is_restore']) && $params['is_restore'] == 'true') {
            $is_restore = true;
            unset($params['is_restore']);
        }

        if (isset($params['is_from_registration']) && $params['is_from_registration']) {
            $is_from_registration = $params['is_from_registration'];
            unset($params['is_from_registration']);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $key = 'id';
            $id = $params['id'];
            $email = '';

            if (isset($params['email']) && $params['email']) {
                $email = $params['email'];
            }

            if (!$old) {
                $old = self::where($key, $id)->first();
            }

            $update = self::where($key, $id)->update($params);

            DB::connection('pgsql_companies')->commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($old['id'])->original
            ]);
        }

        $users = self::create([
            'password' => $params['password'],
            'name' => $params['name'],
            'email' => $params['email'],
            'username' => $params['email'],
            'phone' => $params['phone'],
            'role_id' => $params['role_id'],
            'employee_id' => $params['employee_id'],
        ]);

        DB::connection('pgsql_companies')->commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($users->id)->original
        ]);
    }

    public static function deleteById($id, $params, $request)
    {
        // $old = self::getById($id)->original;

        self::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data'
        ]);
    }

    public static function approveById($id, $params, $request)
    {
        // $data = self::getById($id)->original;

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Approved Data',
            'data' => null
        ]);
    }

    public static function generateToken($params, $method, $request)
    {
        $request->validate([
            'phone' => 'string',
            'password' => 'string',
            'email' => 'string'
        ]);
        
        if (isset($params['phone'])) {
            $user_key = 'phone';
            $user_value = $params['phone'];
        } else {
            $user_key = 'email';
            $user_value = $params['email'];
        }
        
        $get_user_detail = User::where($user_key, $user_value)->first();

        $credentials = request([$user_key, 'password']);

        if(!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unknown '.$user_key.' and Password'
            ], 401);
        }
        
        $user = $request->user();
        
        $tokenResult = $user->createToken('tokens');

        $token = $tokenResult->plainTextToken;

        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'data' => $get_user_detail
        ]);
    }

    public static function authorizes($params, $method, $request)
    {
        $request->session()->flush();
        $request->session()->put('_login', true);
        $request->session()->put('_id', $params['id']);
        $request->session()->put('_name', $params['name']);
        $request->session()->put('_email', $params['email']);
        $request->session()->put('_phone', $params['phone']);
        $request->session()->put('_fcm_token', $params['fcm_token']);
        $request->session()->put('_username', $params['username']);
        $request->session()->put('_is_suspend', $params['is_suspend']);
        $request->session()->put('_role_id', $params['role_id']);
        $request->session()->put('_department_id', $params['department_id']);
        $request->session()->put('_branch_id', $params['branch_id']);
        $request->session()->put('_employee_id', $params['employee_id']);
        $request->session()->put('_access_token', $params['access_token']);
        $request->session()->put('_is_access_to_pos', false);

        $general_settings = GeneralSettings::select('key', 'value', 'type')->get()->toArray();

        foreach ($general_settings as $general_setting) {
            $request->session()->put('general_settings.'.$general_setting['key'], $general_setting['value']);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
