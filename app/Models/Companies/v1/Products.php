<?php

namespace App\Models\Companies\v1;

use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string code
 * @property string name
 * @property string description
 * @property string multi_price_type
 * @property string brand
 * @property string uid
 * @property int    product_category_id
 * @property int    product_type_id
 * @property int    unit_id
 * @property int    sale_price
 * @property int    purchase_price
 * @property int    sale_tax
 * @property int    purchase_tax
 * @property int    width
 * @property int    height
 * @property int    length
 * @property int    weight
 * @property int    is_active
 * @property int    product_base_id
 * @property int    is_serial_number
 * @property int    purchase_tax_id
 * @property int    sale_tax_id
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class Products extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql_companies';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

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
		'product_category_id',
		'product_type_id',
		'unit_id',
		'sale_price',
		'purchase_price',
		'description',
		'sale_tax',
		'purchase_tax',
		'width',
		'height',
		'length',
		'weight',
		'is_active',
		'multi_price_type',
		'brand',
		'product_base_id',
		'is_serial_number',
		'purchase_tax_id',
		'sale_tax_id',
		'uid',
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
        'code' => 'string', 'name' => 'string', 'product_category_id' => 'int', 'product_type_id' => 'int', 'unit_id' => 'int', 'sale_price' => 'int', 'purchase_price' => 'int', 'description' => 'string', 'sale_tax' => 'int', 'purchase_tax' => 'int', 'width' => 'int', 'height' => 'int', 'length' => 'int', 'weight' => 'int', 'is_active' => 'int', 'multi_price_type' => 'string', 'brand' => 'string', 'product_base_id' => 'int', 'is_serial_number' => 'int', 'purchase_tax_id' => 'int', 'sale_tax_id' => 'int', 'uid' => 'string', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
    public function product_variants()
    {
        return $this->hasMany(ProductVariants::class, 'product_id', 'id')->orderBy('sequence');
    }

    public function media()
    {
        return $this->hasMany(Media::class, 'model_id', 'id')->where('model', 'Products');
    }
        
    public function unit_conversions()
    {
            
        return $this->hasMany(ProductUnitConversions::class, 'product_id', 'id');
    }

    public function multi_prices()
    { 
        return $this->hasMany(ProductMultiPrices::class, 'product_id', 'id');
    }

    public function product_skus()
    { 
        return $this->hasMany(ProductSkus::class, 'product_id', 'id');
    }

    public function unit()
    { 
        return $this->hasOne(Units::class, 'id', 'unit_id');
    }


    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;
        $warehouse_id = ($params['warehouse_id'] ?? '');

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
				'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
				'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
				'product_category_id' => ['column' => $model->table.'.product_category_id', 'alias' => 'product_category_id', 'type' => 'int'],
                'category_name' => ['column' => 'a.name', 'alias' => 'category_name', 'type' => 'string'],
                'category_code' => ['column' => 'a.code', 'alias' => 'category_code', 'type' => 'string'],
				'product_type_id' => ['column' => $model->table.'.product_type_id', 'alias' => 'product_type_id', 'type' => 'int'],
				'unit_id' => ['column' => $model->table.'.unit_id', 'alias' => 'unit_id', 'type' => 'int'],
				'unit_name' => ['column' => 'units.name', 'alias' => 'unit_name', 'type' => 'string'],
				'unit_code' => ['column' => 'units.code', 'alias' => 'unit_code', 'type' => 'string'],
				'sale_price' => ['column' => $model->table.'.sale_price', 'alias' => 'sale_price', 'type' => 'int'],
				'purchase_price' => ['column' => $model->table.'.purchase_price', 'alias' => 'purchase_price', 'type' => 'int'],
				'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
				'sale_tax' => ['column' => $model->table.'.sale_tax', 'alias' => 'sale_tax', 'type' => 'int'],
				'purchase_tax' => ['column' => $model->table.'.purchase_tax', 'alias' => 'purchase_tax', 'type' => 'int'],
				'sale_tax_id' => ['column' => $model->table.'.sale_tax_id', 'alias' => 'sale_tax_id', 'type' => 'int'],
				'sale_tax_name' => ['column' => 'sale_tax.name', 'alias' => 'sale_tax_name', 'type' => 'string'],
				'sale_tax_code' => ['column' => 'sale_tax.code', 'alias' => 'sale_tax_code', 'type' => 'string'],
				'purchase_tax_id' => ['column' => $model->table.'.purchase_tax_id', 'alias' => 'purchase_tax_id', 'type' => 'int'],
				'purchase_tax_name' => ['column' => 'purchase_tax.name', 'alias' => 'purchase_tax_name', 'type' => 'string'],
				'purchase_tax_code' => ['column' => 'purchase_tax.code', 'alias' => 'purchase_tax_code', 'type' => 'string'],
				'width' => ['column' => $model->table.'.width', 'alias' => 'width', 'type' => 'int'],
				'height' => ['column' => $model->table.'.height', 'alias' => 'height', 'type' => 'int'],
				'length' => ['column' => $model->table.'.length', 'alias' => 'length', 'type' => 'int'],
				'weight' => ['column' => $model->table.'.weight', 'alias' => 'weight', 'type' => 'int'],
				'is_active' => ['column' => $model->table.'.is_active', 'alias' => 'is_active', 'type' => 'int'],
				'multi_price_type' => ['column' => $model->table.'.multi_price_type', 'alias' => 'multi_price_type', 'type' => 'string'],
				'brand' => ['column' => $model->table.'.brand', 'alias' => 'brand', 'type' => 'string'],
				'product_base_id' => ['column' => $model->table.'.product_base_id', 'alias' => 'product_base_id', 'type' => 'int'],
				'is_serial_number' => ['column' => $model->table.'.is_serial_number', 'alias' => 'is_serial_number', 'type' => 'int'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
				'uid' => ['column' => $model->table.'.uid', 'alias' => 'uid', 'type' => 'string'],
				'qty_on_hand' => ['column' => 'COALESCE(inventories.qty, 0)', 'alias' => 'qty_on_hand', 'type' => 'string', 'is_raw' => true],
                'is_product_unit_convert' => ['column' => "CASE WHEN EXISTS (SELECT 1 FROM product_unit_conversions puc WHERE puc.product_id = {$model->table}.id AND puc.deleted_at IS NULL)THEN 1 ELSE 0 END", 'alias' => 'is_product_unit_convert', 'type' => 'int', 'is_raw' => true],
            ],
            'join' => [
                ['table' => 'product_categories as a', 'type' => 'left', 'on' => ['a.id', '=', $model->table . '.product_category_id']],
                ['table' => 'units', 'type' => 'left', 'on' => ['units.id', '=', $model->table . '.unit_id']],
                ['table' => 'taxes as purchase_tax', 'type' => 'left', 'on' => ['purchase_tax.id', '=', $model->table . '.purchase_tax_id']],
                ['table' => 'taxes as sale_tax', 'type' => 'left', 'on' => ['sale_tax.id', '=', $model->table . '.sale_tax_id']],
                ['table' => DB::raw("
                        (
                            SELECT
                                ph.product_id,
                                SUM(
                                    CASE
                                        WHEN ph.type = 'IN' THEN ph.qty
                                        WHEN ph.type = 'OUT' THEN -ph.qty
                                        ELSE 0
                                    END
                                ) AS qty
                            FROM product_histories ph
                            WHERE ph.deleted_at IS NULL
                            ".($warehouse_id ? "AND ph.warehouse_id = {$warehouse_id}" : "")."
                            GROUP BY ph.product_id
                        ) as inventories
                    "),
                    'type' => 'left',
                    'on' => ['inventories.product_id', '=', $model->table . '.id'],
                ],
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
        $schema = self::mapSchema($params);
        $is_simple = false;
        
        $models = new self;

        $paramsPage = isset($params['page']) ? $params['page'] : 0;
        
        $or = [];
        $date = null;

        unset($params['page']);

        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }

        if (isset($params['is_simple']) && $params['is_simple'] == 'true') {
            $is_simple = true;
            unset($params['is_simple']);
        }

        $db = ModelHelper::select($schema['field'], $request, __CLASS__);

        $eloquent_relations = [];
        
        $with = $params['with'] ?? [];
        
        if (is_string($with)) {
            $with = array_map('trim', explode(',', $with));
        }
        
        $with = array_filter((array) $with);
        
        $model = is_object($models) ? $models : new $models();
        
        foreach ($with as $relation) {
        
            $root = explode('.', $relation)[0];
        
            if (!method_exists($model, $root)) {
                continue;
            }
        
            try {
                $result = $model->$root();
        
                if ($result instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                    $eloquent_relations[] = $relation;
                }
        
            } catch (\Throwable $e) {
                continue;
            }
        }
        
        $eloquent_relations = array_values(array_unique($eloquent_relations));
        
        if (!$is_simple) {
            if ($eloquent_relations) {
                // if (in_array('multi_prices', $eloquent_relations)) {
                //     $db->with(['multi_prices' => function ($q) {
                //         $q->leftJoin('contact_groups', 'contact_groups.id', '=', 'product_multi_prices.contact_group_id')
                //         ->leftJoin('branches', 'branches.id', '=', 'product_multi_prices.branch_id')
                //         ->select(
                //           'product_multi_prices.*',
                //           'branches.name as branch_name',
                //           'branches.code as branch_code',
                //           'contact_groups.name as contact_group_name',
                //         );
                //     }]);
    
                //     $key = array_search('multi_prices', $eloquent_relations);
    
                //     if ($key !== false) {
                //         unset($eloquent_relations[$key]);
                //     }
                // }
                
                if (in_array('product_skus.product_sku_variants', $eloquent_relations)) {
                    $db->with(['product_skus.product_sku_variants' => function($q) {
                        $q->leftJoin('variants', 'variants.id', '=', 'product_sku_variants.variant_id')
                          ->leftJoin('variant_options', 'variant_options.id', '=', 'product_sku_variants.option_id')
                          ->select(
                              'product_sku_variants.*',
                              'variants.name as variant_name',
                              'variant_options.value as option_value'
                          );
                    }]);
    
                    $key = array_search('product_skus.product_sku_variants', $eloquent_relations);
    
                    if ($key !== false) {
                        unset($eloquent_relations[$key]);
                    }
                }
    
                if (in_array('multi_prices', $eloquent_relations)) {
                    $db->with(['multi_prices' => function ($q) {
                        $q->leftJoin('contact_groups', 'contact_groups.id', '=', 'product_multi_prices.contact_group_id')
                        ->leftJoin('branches', 'branches.id', '=', 'product_multi_prices.branch_id')
                        ->leftJoin('units', 'units.id', '=', 'product_multi_prices.unit_id')
                        ->leftJoin('product_skus', 'product_skus.id', '=', 'product_multi_prices.product_sku_id')
                        ->select(
                          'product_multi_prices.*',
                          'branches.name as branch_name',
                          'branches.code as branch_code',
                          'contact_groups.name as contact_group_name',
                          'units.name as unit_name',
                          'product_skus.alias as product_sku_name',
                          'product_skus.sku_code as product_sku_code',
                        );
                    }]);
    
                    $key = array_search('multi_prices', $eloquent_relations);
    
                    if ($key !== false) {
                        unset($eloquent_relations[$key]);
                    }
                }
    
                $db->with($eloquent_relations);
            }
    
            $db->with(['unit_conversions' => function ($q) {
                $q->leftJoin('units as form_unit', 'form_unit.id', '=', 'product_unit_conversions.from_unit_id')
                ->leftJoin('units as to_unit', 'to_unit.id', '=', 'product_unit_conversions.to_unit_id')
                ->select(
                  'product_unit_conversions.*',
                  'form_unit.name as from_unit_name',
                  'to_unit.name as to_unit_name',
                );
            }]);
        }

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
