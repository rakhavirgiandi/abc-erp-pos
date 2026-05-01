<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string company_id
 * @property string db_driver
 * @property string db_host
 * @property string db_username
 * @property string db_password
 * @property string db_database
 * @property string db_port
 * @property string private_ip
 * @property string public_ip
 * @property int    deleted_at
 * @property int    created_at
 * @property int    updated_at
 */
class CompanyCredentials extends Model
{
    use SoftDeletes;
    protected $connection = 'pgsql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_credentials';

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
		'db_driver',
		'db_host',
		'db_username',
		'db_password',
		'db_database',
		'db_port',
		'private_ip',
		'public_ip',
		'deleted_at',
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
        'company_id' => 'string', 'db_driver' => 'string', 'db_host' => 'string', 'db_username' => 'string', 'db_password' => 'string', 'db_database' => 'string', 'db_port' => 'string', 'private_ip' => 'string', 'public_ip' => 'string', 'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
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

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'string'],
				'company_id' => ['column' => $model->table.'.company_id', 'alias' => 'company_id', 'type' => 'string'],
				'db_driver' => ['column' => $model->table.'.db_driver', 'alias' => 'db_driver', 'type' => 'string'],
				'db_host' => ['column' => $model->table.'.db_host', 'alias' => 'db_host', 'type' => 'string'],
				'db_username' => ['column' => $model->table.'.db_username', 'alias' => 'db_username', 'type' => 'string'],
				'db_password' => ['column' => $model->table.'.db_password', 'alias' => 'db_password', 'type' => 'string'],
				'db_database' => ['column' => $model->table.'.db_database', 'alias' => 'db_database', 'type' => 'string'],
				'db_port' => ['column' => $model->table.'.db_port', 'alias' => 'db_port', 'type' => 'string'],
				'private_ip' => ['column' => $model->table.'.private_ip', 'alias' => 'private_ip', 'type' => 'string'],
				'public_ip' => ['column' => $model->table.'.public_ip', 'alias' => 'public_ip', 'type' => 'string'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
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
