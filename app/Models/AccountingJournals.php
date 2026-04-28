<?php

namespace App\Models;

use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Date   date
 * @property string number
 * @property string ref_number
 * @property string description
 * @property string type
 * @property string currency_name
 * @property string branch_name
 * @property string project_name
 * @property string model
 * @property string status
 * @property int    currency_id
 * @property int    branch_id
 * @property int    project_id
 * @property int    model_id
 * @property int    total
 * @property int    exchange_rate
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class AccountingJournals extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'accounting_journals';

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
        'date',
		'number',
		'ref_number',
		'description',
		'type',
		'currency_id',
		'currency_name',
		'branch_id',
		'branch_name',
		'project_id',
		'project_name',
		'model',
		'model_id',
		'total',
		'exchange_rate',
		'status',
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
        'date' => 'date', 'number' => 'string', 'ref_number' => 'string', 'description' => 'string', 'type' => 'string', 'currency_id' => 'int', 'currency_name' => 'string', 'branch_id' => 'int', 'branch_name' => 'string', 'project_id' => 'int', 'project_name' => 'string', 'model' => 'string', 'model_id' => 'int', 'total' => 'int', 'exchange_rate' => 'int', 'status' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
				'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'date'],
				'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
				'ref_number' => ['column' => $model->table.'.ref_number', 'alias' => 'ref_number', 'type' => 'string'],
				'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
				'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'string'],
				'currency_id' => ['column' => $model->table.'.currency_id', 'alias' => 'currency_id', 'type' => 'int'],
				'currency_name' => ['column' => $model->table.'.currency_name', 'alias' => 'currency_name', 'type' => 'string'],
				'branch_id' => ['column' => $model->table.'.branch_id', 'alias' => 'branch_id', 'type' => 'int'],
				'branch_name' => ['column' => $model->table.'.branch_name', 'alias' => 'branch_name', 'type' => 'string'],
				'project_id' => ['column' => $model->table.'.project_id', 'alias' => 'project_id', 'type' => 'int'],
				'project_name' => ['column' => $model->table.'.project_name', 'alias' => 'project_name', 'type' => 'string'],
				'model' => ['column' => $model->table.'.model', 'alias' => 'model', 'type' => 'string'],
				'model_id' => ['column' => $model->table.'.model_id', 'alias' => 'model_id', 'type' => 'int'],
				'total' => ['column' => $model->table.'.total', 'alias' => 'total', 'type' => 'int'],
				'exchange_rate' => ['column' => $model->table.'.exchange_rate', 'alias' => 'exchange_rate', 'type' => 'int'],
				'status' => ['column' => $model->table.'.status', 'alias' => 'status', 'type' => 'string'],
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
        DB::beginTransaction();

        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $old = self::getById($params['id'])->original;

            $update = self::where('id', $params['id'])->update($params);

            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($params['id'])->original
            ]);
        }

        $save = self::create($params);

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($save->id)->original
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
}
