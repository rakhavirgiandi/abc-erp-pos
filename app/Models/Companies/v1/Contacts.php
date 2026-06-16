<?php

namespace App\Models\Companies\v1;

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

    protected $connection = 'pgsql_companies';

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
				'is_active' => ['column' => $model->table.'.is_active', 'alias' => 'is_active', 'type' => 'int'],
				'salesman_id' => ['column' => $model->table.'.salesman_id', 'alias' => 'salesman_id', 'type' => 'int'],
				'salesman_name' => ['column' => $model->table.'.salesman_name', 'alias' => 'salesman_name', 'type' => 'string'],
				'currency_id' => ['column' => $model->table.'.currency_id', 'alias' => 'currency_id', 'type' => 'int'],
				'contact_group_id' => ['column' => $model->table.'.contact_group_id', 'alias' => 'contact_group_id', 'type' => 'int'],
				'contact_group_name' => ['column' => 'contact_groups.name', 'alias' => 'contact_group_name', 'type' => 'string'],
				'currency_name' => ['column' => 'currencies.name', 'alias' => 'currency_name', 'type' => 'string'],
				'due_days' => ['column' => $model->table.'.due_days', 'alias' => 'due_days', 'type' => 'int'],
				'early_discount' => ['column' => $model->table.'.early_discount', 'alias' => 'early_discount', 'type' => 'int'],
				'late_fees' => ['column' => $model->table.'.late_fees', 'alias' => 'late_fees', 'type' => 'int'],
                'point_balance' => [
                    'column' => '(
                        COALESCE((
                            SELECT SUM(ph.point)
                            FROM point_histories ph
                            WHERE ph.contact_id = contacts.id
                            AND ph.type = \'in\'
                            AND ph.deleted_at IS NULL
                        ),0)
                        -
                        COALESCE((
                            SELECT SUM(ph.point)
                            FROM point_histories ph
                            WHERE ph.contact_id = contacts.id
                            AND ph.type = \'out\'
                            AND ph.deleted_at IS NULL
                        ),0)
                    )',
                    'alias' => 'point_balance',
                    'type' => 'int',
                    'is_raw' => true
                ],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date']
                ],
            'join' => [
                ['table' => 'currencies', 'type' => 'left', 'on' => ['currencies.id', '=', $model->table . '.currency_id']],
                ['table' => 'contact_groups', 'type' => 'left', 'on' => ['contact_groups.id', '=', $model->table . '.contact_group_id']],
            
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
        if (!empty($filter)) {
            if (isset($filter) && $filter['is_active'] != 'all') {
                $qry->where('contacts.is_active',$filter['is_active']);
            }
            if (isset($filter['is_seller'])) {
                $qry->where('is_seller',$filter['is_seller']);
            }
            if (isset($filter['is_customer'])) {
                $qry->where('is_customer',$filter['is_customer']);
            }
            if (isset($filter['is_supplier'])) {
                $qry->where('is_supplier',$filter['is_supplier']);
            }
            if (isset($filter['is_staff'])) {
                $qry->where('is_staff',$filter['is_staff']);
            }
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

        $is_pos_display = 0;

        unset($params['page']);

        if (isset($params['or']) && $params['or']) {
            $or = $params['or'];
            unset($params['or']);
        }

        if (isset($params['is_pos_display']) && $params['is_pos_display']) {
            $is_pos_display = $params['is_pos_display'];
            unset($params['is_pos_display']);
        }

        $field = $schema['field'];

        if ($is_pos_display) {
            $select = ['id', 'name', 'code', 'point_balance', 'email', 'phone', 'contact_group_id', 'contact_group_name', 'point_balance'];
            $field = array_intersect_key($field, array_flip($select));
        }

        $db = ModelHelper::select($field, $request, __CLASS__);
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

        $field = $schema['field'];
        
        $is_pos_display = 0;

        if (isset($params['is_pos_display']) && $params['is_pos_display']) {
            $is_pos_display = $params['is_pos_display'];
            unset($params['is_pos_display']);
        }

        if ($is_pos_display) {
            $select = ['id', 'name', 'code', 'point_balance', 'email', 'phone', 'contact_group_id', 'contact_group_name', 'point_balance', 'is_active'];
            $field = array_intersect_key($field, array_flip($select));
        }
        
        $db = ModelHelper::select($field, $request, __CLASS__)->where($models->table.'.id', $id);
        
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

    public static function resetRewardPoints($params)
    {
        $rules = [
            'contact_ids' => 'required_without:contact_group_ids|array',
            'contact_ids.*' => 'integer',
            'contact_group_ids' => 'required_without:contact_ids|array',
            'contact_group_ids.*' => 'integer',
        ];

        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->all()
            ], 422);
        }

        $contact_ids = [];
        
        if (isset($params['contact_ids']) && $params['contact_ids']) {
            $contact_ids = $params['contact_ids'];
        }

        $contact_group_ids = [];

        if (isset($params['contact_group_ids']) && $params['contact_group_ids']) {
            $contact_group_ids = $params['contact_group_ids'];
        }

        DB::connection('pgsql_companies')->beginTransaction();

        $contact_qry = Contacts::where('is_customer', 1);

        if (count($contact_ids) > 0) {
            $contact_qry->whereIn('id', $contact_ids);
        }

        if (count($contact_group_ids) > 0) {
            $contact_qry->whereIn('contact_group_id', $contact_group_ids);
        }

        if (count($contact_group_ids) > 0 || count($contact_ids) > 0) {
            $contact_by_ids = $contact_qry->get()->pluck('id')->toArray();

            PointHistories::whereIn('contact_id', $contact_by_ids)->delete();
        }

        DB::connection('pgsql_companies')->commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Reset Point',
            'data' => null
        ]);
    }
}
