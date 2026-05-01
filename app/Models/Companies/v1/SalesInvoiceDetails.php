<?php

namespace App\Models\Companies\v1;

use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int    sales_invoice_id
 * @property int    product_id
 * @property int    qty
 * @property int    unit_id
 * @property int    unit_price
 * @property int    discount_amount
 * @property int    sales_delivery_id
 * @property int    discount_percentage
 * @property int    other_cost
 * @property int    tax_amount
 * @property int    tax_percentage
 * @property int    other_income
 * @property int    tax_id
 * @property int    sales_return_qty
 * @property int    base_qty
 * @property int    base_unit_price
 * @property int    base_unit_id
 * @property int    is_product_unit_convert
 * @property int    sales_order_detail_id
 * @property int    product_sku_id
 * @property int    reward_point_id
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 * @property string product_code
 * @property string product_name
 * @property string unit_name
 * @property string note
 * @property string discount_type
 * @property string discount_coa
 * @property string service_name
 * @property string coa
 * @property string sales_delivery_name
 * @property string ref_number
 * @property string other_coa
 * @property string tax_coa
 * @property string sales_delivery_number
 * @property string other_income_coa
 */
class SalesInvoiceDetails extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql_companies';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_invoice_details';

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
        'sales_invoice_id',
		'product_id',
		'product_code',
		'product_name',
		'qty',
		'unit_id',
		'unit_name',
		'unit_price',
		'note',
		'discount_type',
		'discount_amount',
		'discount_coa',
		'service_name',
		'coa',
		'sales_delivery_id',
		'sales_delivery_name',
		'ref_number',
		'discount_percentage',
		'other_cost',
		'other_coa',
		'tax_amount',
		'tax_percentage',
		'tax_coa',
		'sales_delivery_number',
		'other_income',
		'other_income_coa',
		'tax_id',
		'sales_return_qty',
		'base_qty',
		'base_unit_price',
		'base_unit_id',
		'is_product_unit_convert',
		'sales_order_detail_id',
		'product_sku_id',
		'reward_point_id',
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
        'sales_invoice_id' => 'int', 'product_id' => 'int', 'product_code' => 'string', 'product_name' => 'string', 'qty' => 'int', 'unit_id' => 'int', 'unit_name' => 'string', 'unit_price' => 'int', 'note' => 'string', 'discount_type' => 'string', 'discount_amount' => 'int', 'discount_coa' => 'string', 'service_name' => 'string', 'coa' => 'string', 'sales_delivery_id' => 'int', 'sales_delivery_name' => 'string', 'ref_number' => 'string', 'discount_percentage' => 'int', 'other_cost' => 'int', 'other_coa' => 'string', 'tax_amount' => 'int', 'tax_percentage' => 'int', 'tax_coa' => 'string', 'sales_delivery_number' => 'string', 'other_income' => 'int', 'other_income_coa' => 'string', 'tax_id' => 'int', 'sales_return_qty' => 'int', 'base_qty' => 'int', 'base_unit_price' => 'int', 'base_unit_id' => 'int', 'is_product_unit_convert' => 'int', 'sales_order_detail_id' => 'int', 'product_sku_id' => 'int', 'reward_point_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
    public function product_detail()
    {
        return $this->hasOne(Products::class, 'id', 'product_id');
    }

    public function product_sku_detail()
    {
        return $this->hasOne(ProductSkus::class, 'id', 'product_sku_id');
    }

    public function unit_detail()
    {
        return $this->hasOne(Units::class, 'id', 'unit_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
				'sales_invoice_id' => ['column' => $model->table.'.sales_invoice_id', 'alias' => 'sales_invoice_id', 'type' => 'int'],
				'product_id' => ['column' => $model->table.'.product_id', 'alias' => 'product_id', 'type' => 'int'],
				'product_code' => ['column' => $model->table.'.product_code', 'alias' => 'product_code', 'type' => 'string'],
				'product_name' => ['column' => $model->table.'.product_name', 'alias' => 'product_name', 'type' => 'string'],
				'qty' => ['column' => $model->table.'.qty', 'alias' => 'qty', 'type' => 'int'],
				'unit_id' => ['column' => $model->table.'.unit_id', 'alias' => 'unit_id', 'type' => 'int'],
				'unit_name' => ['column' => $model->table.'.unit_name', 'alias' => 'unit_name', 'type' => 'string'],
				'unit_price' => ['column' => $model->table.'.unit_price', 'alias' => 'unit_price', 'type' => 'int'],
				'note' => ['column' => $model->table.'.note', 'alias' => 'note', 'type' => 'string'],
				'discount_type' => ['column' => $model->table.'.discount_type', 'alias' => 'discount_type', 'type' => 'string'],
				'discount_amount' => ['column' => $model->table.'.discount_amount', 'alias' => 'discount_amount', 'type' => 'int'],
				'discount_coa' => ['column' => $model->table.'.discount_coa', 'alias' => 'discount_coa', 'type' => 'string'],
				'service_name' => ['column' => $model->table.'.service_name', 'alias' => 'service_name', 'type' => 'string'],
				'coa' => ['column' => $model->table.'.coa', 'alias' => 'coa', 'type' => 'string'],
				'sales_delivery_id' => ['column' => $model->table.'.sales_delivery_id', 'alias' => 'sales_delivery_id', 'type' => 'int'],
				'sales_delivery_name' => ['column' => $model->table.'.sales_delivery_name', 'alias' => 'sales_delivery_name', 'type' => 'string'],
				'ref_number' => ['column' => $model->table.'.ref_number', 'alias' => 'ref_number', 'type' => 'string'],
				'discount_percentage' => ['column' => $model->table.'.discount_percentage', 'alias' => 'discount_percentage', 'type' => 'int'],
				'other_cost' => ['column' => $model->table.'.other_cost', 'alias' => 'other_cost', 'type' => 'int'],
				'other_coa' => ['column' => $model->table.'.other_coa', 'alias' => 'other_coa', 'type' => 'string'],
				'tax_amount' => ['column' => $model->table.'.tax_amount', 'alias' => 'tax_amount', 'type' => 'int'],
				'tax_percentage' => ['column' => $model->table.'.tax_percentage', 'alias' => 'tax_percentage', 'type' => 'int'],
				'tax_coa' => ['column' => $model->table.'.tax_coa', 'alias' => 'tax_coa', 'type' => 'string'],
				'sales_delivery_number' => ['column' => $model->table.'.sales_delivery_number', 'alias' => 'sales_delivery_number', 'type' => 'string'],
				'other_income' => ['column' => $model->table.'.other_income', 'alias' => 'other_income', 'type' => 'int'],
				'other_income_coa' => ['column' => $model->table.'.other_income_coa', 'alias' => 'other_income_coa', 'type' => 'string'],
				'tax_id' => ['column' => $model->table.'.tax_id', 'alias' => 'tax_id', 'type' => 'int'],
				'sales_return_qty' => ['column' => $model->table.'.sales_return_qty', 'alias' => 'sales_return_qty', 'type' => 'int'],
				'base_qty' => ['column' => $model->table.'.base_qty', 'alias' => 'base_qty', 'type' => 'int'],
				'base_unit_price' => ['column' => $model->table.'.base_unit_price', 'alias' => 'base_unit_price', 'type' => 'int'],
				'base_unit_id' => ['column' => $model->table.'.base_unit_id', 'alias' => 'base_unit_id', 'type' => 'int'],
				'is_product_unit_convert' => ['column' => $model->table.'.is_product_unit_convert', 'alias' => 'is_product_unit_convert', 'type' => 'int'],
				'sales_order_detail_id' => ['column' => $model->table.'.sales_order_detail_id', 'alias' => 'sales_order_detail_id', 'type' => 'int'],
				'product_sku_id' => ['column' => $model->table.'.product_sku_id', 'alias' => 'product_sku_id', 'type' => 'int'],
				'reward_point_id' => ['column' => $model->table.'.reward_point_id', 'alias' => 'reward_point_id', 'type' => 'int'],
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
