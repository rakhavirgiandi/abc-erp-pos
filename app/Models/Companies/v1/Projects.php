<?php

namespace App\Models\Companies\v1;

use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

/**
 * @property string code
 * @property string name
 * @property string status
 * @property string order_number
 * @property string description
 * @property Date   order_date
 * @property Date   delivery_date
 * @property int    manager_project_id
 * @property int    customer_id
 * @property int    percentage_done
 * @property int    estimated_cost
 * @property int    budget_amount
 * @property int    is_active
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class Projects extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql_companies';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'projects';

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
        'code',
		'name',
		'order_date',
		'delivery_date',
		'manager_project_id',
		'customer_id',
		'status',
		'percentage_done',
		'order_number',
		'estimated_cost',
		'budget_amount',
		'description',
		'is_active',
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
        'code' => 'string', 'name' => 'string', 'order_date' => 'date', 'delivery_date' => 'date', 'manager_project_id' => 'int', 'customer_id' => 'int', 'status' => 'string', 'percentage_done' => 'int', 'order_number' => 'string', 'estimated_cost' => 'int', 'budget_amount' => 'int', 'description' => 'string', 'is_active' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
				'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
				'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
				'order_date' => ['column' => $model->table.'.order_date', 'alias' => 'order_date', 'type' => 'date'],
				'delivery_date' => ['column' => $model->table.'.delivery_date', 'alias' => 'delivery_date', 'type' => 'date'],
				'manager_project_id' => ['column' => $model->table.'.manager_project_id', 'alias' => 'manager_project_id', 'type' => 'int'],
				'manager_project_name' => ['column' => 'manager_project.name', 'alias' => 'manager_project_name', 'type' => 'string'],
				'customer_id' => ['column' => $model->table.'.customer_id', 'alias' => 'customer_id', 'type' => 'int'],
				'customer_name' => ['column' => 'customer.name', 'alias' => 'customer_name', 'type' => 'string'],
				'status' => ['column' => $model->table.'.status', 'alias' => 'status', 'type' => 'string'],
				'percentage_done' => ['column' => $model->table.'.percentage_done', 'alias' => 'percentage_done', 'type' => 'int'],
				'order_number' => ['column' => $model->table.'.order_number', 'alias' => 'order_number', 'type' => 'string'],
				'estimated_cost' => ['column' => $model->table.'.estimated_cost', 'alias' => 'estimated_cost', 'type' => 'int'],
				'budget_amount' => ['column' => $model->table.'.budget_amount', 'alias' => 'budget_amount', 'type' => 'int'],
				'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
				'is_active' => ['column' => $model->table.'.is_active', 'alias' => 'is_active', 'type' => 'int'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
            ],
            'join' => [
                ['table' => 'contacts as manager_project', 'type' => 'left', 'on' => ['manager_project.id', '=', $model->table . '.manager_project_id']],
                ['table' => 'contacts as customer', 'type' => 'left', 'on' => ['customer.id', '=', $model->table . '.customer_id']],
            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $schema = self::mapSchema();
        $model = new self;

        $totalData = self::count();

        $qry = ModelHelper::select($schema['field'], null, __CLASS__);
        ModelHelper::join($schema['join'], null, $qry);
        
        //FILTER
        if (isset($filter) && $filter['is_active'] != 'all') {
            $qry->where($model->table.'.is_active', $filter['is_active']);
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

    public static function validate($params)
    {
        $rules = [
            'name'=>'required',
            'code'=> ['required', Rule::unique(self::class, 'code')->ignore($params['id'] ?? '')->whereNull('deleted_at')],
            'customer_id'=>'required',
        ];

        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->all()
            ], 422);
        }

        return true;
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::beginTransaction();

        $validation = self::validate($params);
        if ($validation !== true) {
            return $validation;
        }

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
