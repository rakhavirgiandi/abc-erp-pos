<?php

namespace App\Models;

use App\Helpers\ModelHelper;
use App\Models\Companies;
use App\Models\CompanyCredentials;
use App\Models\Subscriptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @property string company_id
 * @property string user_id
 * @property int    created_at
 * @property int    updated_at
 */
class UserCompanies extends Model
{
    use SoftDeletes;
    protected $connection = 'pgsql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_companies';

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
        'company_id',
		'user_id',
        'type',
		'created_at',
		'updated_at',
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
        'company_id' => 'string', 'user_id' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
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
    public function company()
    {
        return $this->hasOne(Companies::class, 'id', 'company_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscriptions::class, 'company_id', 'company_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'string'],
				'company_id' => ['column' => $model->table.'.company_id', 'alias' => 'company_id', 'type' => 'string'],
				'user_id' => ['column' => $model->table.'.user_id', 'alias' => 'user_id', 'type' => 'string'],
                'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'int'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
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
        $user = $request->user();
        $or = [];
        
        $is_filter_by_user = true;
        $type = 'member';

        if (isset($params['is_filter_by_user']) && $params['is_filter_by_user'] == 'false') {
            $is_filter_by_user = false;
        }
        unset($params['is_filter_by_user']);
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

        if (isset($params['type']) && $params['type']) {
            $type = $params['type'];
        }

        if ($is_filter_by_user) {
            $db->where('user_id', $user->id)->with('company')->with('subscription');
        }

        $db->where('type', $type);

        $results = ModelHelper::generateAllResults($schema, $params, $request, $db, $append);

        $company_ids = [];

        foreach ($results as $row) {
            $company_ids[] = $row['company_id'];
        }

        $company_credentials = CompanyCredentials::select('id', 'db_database')->whereIn('company_id', $company_ids)->get()->pluck('db_database', 'id')->toArray();

        foreach ($results as $key => $row) {
            $results[$key]['slug'] = null;

            dd($row['company_id'], $company_credentials[$row['company_id']]);
            if (isset($company_credentials[$row['company_id']]) && $company_credentials[$row['company_id']]) {
                $results[$key]['slug'] = $company_credentials[$row['company_id']];
            }
        }

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

        $save = self::create($params);

        DB::connection('pgsql')->commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($save->id)->original
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
}
