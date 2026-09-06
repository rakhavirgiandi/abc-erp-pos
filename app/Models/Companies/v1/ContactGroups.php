<?php

namespace App\Models\Companies\v1;

use Carbon\Carbon;
use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string code
 * @property string name
 * @property int    is_active
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class ContactGroups extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql_companies';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contact_groups';

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
        'code' => 'string', 'name' => 'string', 'is_active' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
				'is_active' => ['column' => $model->table.'.is_active', 'alias' => 'is_active', 'type' => 'int'],
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

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $old = self::getById($params['id'])->original;

            $update = self::where('id', $params['id'])->update($params);

            DB::connection('pgsql_companies')->commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($params['id'])->original
            ]);
        }

        $save = self::create($params);

        DB::connection('pgsql_companies')->commit();
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

    public static function generateRewardPoints($id, $params)
    {
    
        $contact_group_id = $id;
        $cart_items = isset($params['chart_items']) ? $params['chart_items'] : [];
        $total_purchase = isset($params['total_purchase']) ? $params['total_purchase'] : 0;
    
        $rules = ContactGroupPointRules::
            where('contact_group_id', $contact_group_id)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expired_date')
                  ->orWhereDate('expired_date', '>=', today());
            })
            ->get()
            ->groupBy('type');
    
        if ($rules->isEmpty()) {
            return 0;
        }
    
        $product_ids = collect($cart_items)->pluck('product_id')->unique()->all();
    
        $products = Products
            ::select('id', 'product_category_id')
            ->whereIn('id', $product_ids)
            ->get()
            ->keyBy('id');
    
        $total_points            = 0;
        $adjusted_total_purchase = $total_purchase;
    
        $points_by_qty = function ($rule, int $cart_qty): int {
            $min_qty = (int) $rule->qty;
    
            if ($min_qty <= 0 || $cart_qty < $min_qty) {
                return 0;
            }
    
            if ($rule->is_applicable_multiple) {
                return (int) floor($cart_qty / $min_qty) * (int) $rule->total_reward_point;
            }
    
            return (int) $rule->total_reward_point;
        };
    
        foreach ($rules->get('product_category', collect()) as $rule) {
            foreach ($cart_items as $item) {
                $product = $products->get($item['product_id']);
    
                if (! $product) {
                    continue;
                }
    
                if ((int) $rule->product_category_id !== (int) $product->product_category_id) {
                    continue;
                }
    
                $total_points += $points_by_qty($rule, (int) $item['qty']);
    
                if ($rule->is_excluded_in_total_payment) {
                    $adjusted_total_purchase -= (float) $item['price'];
                }
            }
        }
    
        foreach ($rules->get('product', collect()) as $rule) {
            foreach ($cart_items as $item) {
                if (! $products->has($item['product_id'])) {
                    continue;
                }
    
                if ((int) $rule->product_category_id !== (int) $item['product_id']) {
                    continue;
                }
    
                if ((int) $rule->unit_id !== (int) $item['unit_id']) {
                    continue;
                }
    
                $total_points += $points_by_qty($rule, (int) $item['qty']);
    
                if ($rule->is_excluded_in_total_payment) {
                    $adjusted_total_purchase -= (float) $item['price'];
                }
            }
        }
    
        $total_purchase_rules = $rules->get('total_purchase', collect());
    
        if ($total_purchase_rules->isNotEmpty()) {
            $effective = max(0.0, $adjusted_total_purchase);
    
            $matched = $total_purchase_rules
                ->filter(fn ($r) => (float) $r->minimum_purchase <= $effective)
                ->sortByDesc('minimum_purchase')
                ->first();
    
            if ($matched) {
                if ($matched->is_applicable_multiple) {
                    $multiplier   = (int) floor($effective / (float) $matched->minimum_purchase);
    
                    $total_points += $multiplier * (int) $matched->total_reward_point;
                } else {
                    $total_points += (int) $matched->total_reward_point;
                }
            }
        }
    
        return max(0, $total_points);
    }
}
