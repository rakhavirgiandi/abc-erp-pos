<?php

namespace App\Models;

use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string name
 * @property string code
 * @property string address
 * @property string phone
 * @property string pic_name
 * @property string pic_phone
 * @property string email
 * @property string npwp
 * @property string npwp_address
 * @property string salesman_name
 * @property int    country_id
 * @property int    city_id
 * @property int    province_id
 * @property int    is_pkp
 * @property int    is_subcon
 * @property int    is_pph_free
 * @property int    is_staff
 * @property int    is_customer
 * @property int    is_supplier
 * @property int    is_seller
 * @property int    is_leads
 * @property int    salesman_id
 * @property int    currency_id
 * @property int    due_days
 * @property int    early_discount
 * @property int    late_fees
 * @property int    is_active
 * @property int    contact_group_id
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class Contacts extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'contacts';

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
		'code',
		'country_id',
		'city_id',
		'province_id',
		'address',
		'phone',
		'pic_name',
		'pic_phone',
		'email',
		'is_pkp',
		'is_subcon',
		'is_pph_free',
		'npwp',
		'npwp_address',
		'is_staff',
		'is_customer',
		'is_supplier',
		'is_seller',
		'is_leads',
		'salesman_id',
		'currency_id',
		'due_days',
		'early_discount',
		'late_fees',
		'is_active',
		'salesman_name',
		'contact_group_id',
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
        'name' => 'string', 'code' => 'string', 'country_id' => 'int', 'city_id' => 'int', 'province_id' => 'int', 'address' => 'string', 'phone' => 'string', 'pic_name' => 'string', 'pic_phone' => 'string', 'email' => 'string', 'is_pkp' => 'int', 'is_subcon' => 'int', 'is_pph_free' => 'int', 'npwp' => 'string', 'npwp_address' => 'string', 'is_staff' => 'int', 'is_customer' => 'int', 'is_supplier' => 'int', 'is_seller' => 'int', 'is_leads' => 'int', 'salesman_id' => 'int', 'currency_id' => 'int', 'due_days' => 'int', 'early_discount' => 'int', 'late_fees' => 'int', 'is_active' => 'int', 'salesman_name' => 'string', 'contact_group_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
				'code' => ['column' => $model->table.'.code', 'alias' => 'code', 'type' => 'string'],
				'country_id' => ['column' => $model->table.'.country_id', 'alias' => 'country_id', 'type' => 'int'],
				'city_id' => ['column' => $model->table.'.city_id', 'alias' => 'city_id', 'type' => 'int'],
				'province_id' => ['column' => $model->table.'.province_id', 'alias' => 'province_id', 'type' => 'int'],
				'address' => ['column' => $model->table.'.address', 'alias' => 'address', 'type' => 'string'],
				'phone' => ['column' => $model->table.'.phone', 'alias' => 'phone', 'type' => 'string'],
				'pic_name' => ['column' => $model->table.'.pic_name', 'alias' => 'pic_name', 'type' => 'string'],
				'pic_phone' => ['column' => $model->table.'.pic_phone', 'alias' => 'pic_phone', 'type' => 'string'],
				'email' => ['column' => $model->table.'.email', 'alias' => 'email', 'type' => 'string'],
				'is_pkp' => ['column' => $model->table.'.is_pkp', 'alias' => 'is_pkp', 'type' => 'int'],
				'is_subcon' => ['column' => $model->table.'.is_subcon', 'alias' => 'is_subcon', 'type' => 'int'],
				'is_pph_free' => ['column' => $model->table.'.is_pph_free', 'alias' => 'is_pph_free', 'type' => 'int'],
				'npwp' => ['column' => $model->table.'.npwp', 'alias' => 'npwp', 'type' => 'string'],
				'npwp_address' => ['column' => $model->table.'.npwp_address', 'alias' => 'npwp_address', 'type' => 'string'],
				'is_staff' => ['column' => $model->table.'.is_staff', 'alias' => 'is_staff', 'type' => 'int'],
				'is_customer' => ['column' => $model->table.'.is_customer', 'alias' => 'is_customer', 'type' => 'int'],
				'is_supplier' => ['column' => $model->table.'.is_supplier', 'alias' => 'is_supplier', 'type' => 'int'],
				'is_seller' => ['column' => $model->table.'.is_seller', 'alias' => 'is_seller', 'type' => 'int'],
				'is_leads' => ['column' => $model->table.'.is_leads', 'alias' => 'is_leads', 'type' => 'int'],
				'salesman_id' => ['column' => $model->table.'.salesman_id', 'alias' => 'salesman_id', 'type' => 'int'],
				'currency_id' => ['column' => $model->table.'.currency_id', 'alias' => 'currency_id', 'type' => 'int'],
				'due_days' => ['column' => $model->table.'.due_days', 'alias' => 'due_days', 'type' => 'int'],
				'early_discount' => ['column' => $model->table.'.early_discount', 'alias' => 'early_discount', 'type' => 'int'],
				'late_fees' => ['column' => $model->table.'.late_fees', 'alias' => 'late_fees', 'type' => 'int'],
				'is_active' => ['column' => $model->table.'.is_active', 'alias' => 'is_active', 'type' => 'int'],
				'salesman_name' => ['column' => $model->table.'.salesman_name', 'alias' => 'salesman_name', 'type' => 'string'],
				'contact_group_id' => ['column' => $model->table.'.contact_group_id', 'alias' => 'contact_group_id', 'type' => 'int'],
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
