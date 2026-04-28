<?php

namespace App\Models;

use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string model
 * @property string ref_number
 * @property string product_code
 * @property string product_name
 * @property string type
 * @property string project_name
 * @property string branch_name
 * @property string unit_name
 * @property string currency_name
 * @property string warehouse_name
 * @property int    model_id
 * @property int    product_id
 * @property int    qty
 * @property int    project_id
 * @property int    branch_id
 * @property int    unit_id
 * @property int    unit_price
 * @property int    currency_id
 * @property int    exchange_rate
 * @property int    warehouse_id
 * @property int    cogs_price
 * @property int    product_sku_id
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 * @property Date   date
 */
class ProductHistories extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_histories';

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
        'model',
		'model_id',
		'ref_number',
		'product_id',
		'product_code',
		'product_name',
		'qty',
		'date',
		'type',
		'project_id',
		'project_name',
		'branch_id',
		'branch_name',
		'unit_id',
		'unit_name',
		'unit_price',
		'currency_id',
		'currency_name',
		'exchange_rate',
		'warehouse_id',
		'warehouse_name',
		'cogs_price',
		'product_sku_id',
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
        'model' => 'string', 'model_id' => 'int', 'ref_number' => 'string', 'product_id' => 'int', 'product_code' => 'string', 'product_name' => 'string', 'qty' => 'int', 'date' => 'date', 'type' => 'string', 'project_id' => 'int', 'project_name' => 'string', 'branch_id' => 'int', 'branch_name' => 'string', 'unit_id' => 'int', 'unit_name' => 'string', 'unit_price' => 'int', 'currency_id' => 'int', 'currency_name' => 'string', 'exchange_rate' => 'int', 'warehouse_id' => 'int', 'warehouse_name' => 'string', 'cogs_price' => 'int', 'product_sku_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
				'model' => ['column' => $model->table.'.model', 'alias' => 'model', 'type' => 'string'],
				'model_id' => ['column' => $model->table.'.model_id', 'alias' => 'model_id', 'type' => 'int'],
				'ref_number' => ['column' => $model->table.'.ref_number', 'alias' => 'ref_number', 'type' => 'string'],
				'product_id' => ['column' => $model->table.'.product_id', 'alias' => 'product_id', 'type' => 'int'],
				'product_code' => ['column' => $model->table.'.product_code', 'alias' => 'product_code', 'type' => 'string'],
				'product_name' => ['column' => $model->table.'.product_name', 'alias' => 'product_name', 'type' => 'string'],
				'qty' => ['column' => $model->table.'.qty', 'alias' => 'qty', 'type' => 'int'],
				'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'date'],
				'type' => ['column' => $model->table.'.type', 'alias' => 'type', 'type' => 'string'],
				'project_id' => ['column' => $model->table.'.project_id', 'alias' => 'project_id', 'type' => 'int'],
				'project_name' => ['column' => $model->table.'.project_name', 'alias' => 'project_name', 'type' => 'string'],
				'branch_id' => ['column' => $model->table.'.branch_id', 'alias' => 'branch_id', 'type' => 'int'],
				'branch_name' => ['column' => $model->table.'.branch_name', 'alias' => 'branch_name', 'type' => 'string'],
				'unit_id' => ['column' => $model->table.'.unit_id', 'alias' => 'unit_id', 'type' => 'int'],
				'unit_name' => ['column' => $model->table.'.unit_name', 'alias' => 'unit_name', 'type' => 'string'],
				'unit_price' => ['column' => $model->table.'.unit_price', 'alias' => 'unit_price', 'type' => 'int'],
				'currency_id' => ['column' => $model->table.'.currency_id', 'alias' => 'currency_id', 'type' => 'int'],
				'currency_name' => ['column' => $model->table.'.currency_name', 'alias' => 'currency_name', 'type' => 'string'],
				'exchange_rate' => ['column' => $model->table.'.exchange_rate', 'alias' => 'exchange_rate', 'type' => 'int'],
				'warehouse_id' => ['column' => $model->table.'.warehouse_id', 'alias' => 'warehouse_id', 'type' => 'int'],
				'warehouse_name' => ['column' => $model->table.'.warehouse_name', 'alias' => 'warehouse_name', 'type' => 'string'],
				'cogs_price' => ['column' => $model->table.'.cogs_price', 'alias' => 'cogs_price', 'type' => 'int'],
				'product_sku_id' => ['column' => $model->table.'.product_sku_id', 'alias' => 'product_sku_id', 'type' => 'int'],
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
