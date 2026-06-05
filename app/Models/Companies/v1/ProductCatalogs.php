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
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class ProductCatalogs extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql_companies';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'product_catalogs';

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
    public function products () {
        return $this->hasMany(Products::class, 'product_catalog_id', 'id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
				'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
				'name' => ['column' => $model->table.'.name', 'alias' => 'name', 'type' => 'string'],
				'product_category_id' => ['column' => $model->table.'.product_category_id', 'alias' => 'product_category_id', 'type' => 'int'],
				'product_type_id' => ['column' => $model->table.'.product_type_id', 'alias' => 'product_type_id', 'type' => 'int'],
				'unit_id' => ['column' => $model->table.'.unit_id', 'alias' => 'unit_id', 'type' => 'int'],
				'sale_price' => ['column' => $model->table.'.sale_price', 'alias' => 'sale_price', 'type' => 'int'],
				'purchase_price' => ['column' => $model->table.'.purchase_price', 'alias' => 'purchase_price', 'type' => 'int'],
				'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
				'sale_tax' => ['column' => $model->table.'.sale_tax', 'alias' => 'sale_tax', 'type' => 'int'],
				'purchase_tax' => ['column' => $model->table.'.purchase_tax', 'alias' => 'purchase_tax', 'type' => 'int'],
				'width' => ['column' => $model->table.'.width', 'alias' => 'width', 'type' => 'int'],
				'height' => ['column' => $model->table.'.height', 'alias' => 'height', 'type' => 'int'],
				'length' => ['column' => $model->table.'.length', 'alias' => 'length', 'type' => 'int'],
				'weight' => ['column' => $model->table.'.weight', 'alias' => 'weight', 'type' => 'int'],
				'is_active' => ['column' => $model->table.'.is_active', 'alias' => 'is_active', 'type' => 'int'],
				'multi_price_type' => ['column' => $model->table.'.multi_price_type', 'alias' => 'multi_price_type', 'type' => 'string'],
				'brand' => ['column' => $model->table.'.brand', 'alias' => 'brand', 'type' => 'string'],
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

        if (isset($params['with_product_details']) && $params['with_product_details']) {
            $db->with(['products' => function ($q)  {
                $q->where('products.is_active', '=', 1);
                $q->leftJoin('units', 'products.unit_id', '=', 'units.id');
                $q->leftJoin('product_categories', 'products.product_category_id', '=', 'product_categories.id');
                $q->leftJoin('taxes as sale_tax', 'products.sale_tax_id', '=', 'sale_tax.id');
                $q->select([
                    'products.id as id',
                    'products.name as name',
                    'products.code as code',
                    'products.product_category_id',
                    'product_categories.name as category_name',
                    'product_categories.code as category_code',
                    'products.unit_id',
                    'units.name as unit_name',
                    'units.code as unit_code',
                    'products.sale_price',
                    'products.product_catalog_id',
                    'products.is_active as is_active',
                    'sale_tax_id',
                    'sale_tax.name as sale_tax_name',
                    'sale_tax.code as sale_tax_code'
                ]);
                $q->with(['product_variants' => function($product_variant_query) {
                    $product_variant_query->leftJoin('variants', 'variants.id', '=', 'product_variants.variant_id')
                      ->leftJoin('variant_options', 'variant_options.id', '=', 'product_variants.variant_option_id')
                      ->select(
                        'product_variants.*',
                        'variants.name as variant_name',
                        'variant_options.value as option_value',
                        'product_variants.sequence as sequence'
                      );
                }]);

                $q->with(['multi_prices' => function ($multi_price_query) {
                    $multi_price_query->leftJoin('contact_groups', 'contact_groups.id', '=', 'product_multi_prices.contact_group_id')
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

                $q->with(['unit_conversions' => function ($unit_convertions_query) {
                    $unit_convertions_query->leftJoin('units as form_unit', 'form_unit.id', '=', 'product_unit_conversions.from_unit_id')
                    ->leftJoin('units as to_unit', 'to_unit.id', '=', 'product_unit_conversions.to_unit_id')
                    ->select(
                      'product_unit_conversions.*',
                      'form_unit.name as from_unit_name',
                      'to_unit.name as to_unit_name',
                    );
                }]);
                $q->with('media');
            }]);
        }

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
