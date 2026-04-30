<?php

namespace App\Models\Companies\v1;

use App\Helpers\AutoNumberHelper;
use App\Helpers\GlobalHelper;
use App\Helpers\ModelDetailHelper;
use DB;
use Illuminate\Support\Str;
use App\Helpers\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string number
 * @property string description
 * @property string sales_order_name
 * @property string delivery_coa
 * @property string other_coa
 * @property string discount_type
 * @property string discount_coa
 * @property string status
 * @property string down_payment_coa
 * @property string coa_cash
 * @property string payment_type
 * @property string tax_name
 * @property string tax_coa
 * @property string branch_name
 * @property string project_name
 * @property string currency_name
 * @property string warehouse_name
 * @property string total_coa
 * @property string sales_quotation_number
 * @property string other_income_coa
 * @property string sales_return_status
 * @property string ref_number
 * @property Date   date
 * @property int    is_from_sales_delivery
 * @property int    sales_order_id
 * @property int    customer_id
 * @property int    salesman_id
 * @property int    top_discount_days
 * @property int    top_due_days
 * @property int    top_early_discount
 * @property int    top_late_charge
 * @property int    delivery_cost
 * @property int    other_cost
 * @property int    discount_amount
 * @property int    down_payment_amount
 * @property int    total
 * @property int    tax_id
 * @property int    tax_amount
 * @property int    tax_percentage
 * @property int    branch_id
 * @property int    project_id
 * @property int    currency_id
 * @property int    exchange_rate
 * @property int    warehouse_id
 * @property int    subtotal
 * @property int    is_standard
 * @property int    sales_quotation_id
 * @property int    discount_percentage
 * @property int    other_income
 * @property int    created_by
 * @property int    total_payment
 * @property int    total_change
 * @property int    is_from_pos
 * @property int    bank_account_id
 * @property int    created_at
 * @property int    updated_at
 * @property int    deleted_at
 */
class SalesInvoices extends Model
{
    use SoftDeletes;

    protected $connection = 'pgsql_companies';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'sales_invoices';

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
        'number',
		'date',
		'description',
		'is_from_sales_delivery',
		'sales_order_id',
		'sales_order_name',
		'customer_id',
		'salesman_id',
		'top_discount_days',
		'top_due_days',
		'top_early_discount',
		'top_late_charge',
		'delivery_cost',
		'delivery_coa',
		'other_cost',
		'other_coa',
		'discount_type',
		'discount_amount',
		'discount_coa',
		'status',
		'down_payment_amount',
		'down_payment_coa',
		'coa_cash',
		'payment_type',
		'total',
		'tax_id',
		'tax_amount',
		'tax_percentage',
		'tax_name',
		'tax_coa',
		'branch_id',
		'branch_name',
		'project_id',
		'project_name',
		'currency_id',
		'currency_name',
		'exchange_rate',
		'warehouse_id',
		'warehouse_name',
		'subtotal',
		'total_coa',
		'is_standard',
		'sales_quotation_id',
		'sales_quotation_number',
		'discount_percentage',
		'other_income',
		'other_income_coa',
		'sales_return_status',
		'created_by',
		'total_payment',
		'total_change',
		'is_from_pos',
		'ref_number',
		'bank_account_id',
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
        'number' => 'string', 'date' => 'date', 'description' => 'string', 'is_from_sales_delivery' => 'int', 'sales_order_id' => 'int', 'sales_order_name' => 'string', 'customer_id' => 'int', 'salesman_id' => 'int', 'top_discount_days' => 'int', 'top_due_days' => 'int', 'top_early_discount' => 'int', 'top_late_charge' => 'int', 'delivery_cost' => 'int', 'delivery_coa' => 'string', 'other_cost' => 'int', 'other_coa' => 'string', 'discount_type' => 'string', 'discount_amount' => 'int', 'discount_coa' => 'string', 'status' => 'string', 'down_payment_amount' => 'int', 'down_payment_coa' => 'string', 'coa_cash' => 'string', 'payment_type' => 'string', 'total' => 'int', 'tax_id' => 'int', 'tax_amount' => 'int', 'tax_percentage' => 'int', 'tax_name' => 'string', 'tax_coa' => 'string', 'branch_id' => 'int', 'branch_name' => 'string', 'project_id' => 'int', 'project_name' => 'string', 'currency_id' => 'int', 'currency_name' => 'string', 'exchange_rate' => 'int', 'warehouse_id' => 'int', 'warehouse_name' => 'string', 'subtotal' => 'int', 'total_coa' => 'string', 'is_standard' => 'int', 'sales_quotation_id' => 'int', 'sales_quotation_number' => 'string', 'discount_percentage' => 'int', 'other_income' => 'int', 'other_income_coa' => 'string', 'sales_return_status' => 'string', 'created_by' => 'int', 'total_payment' => 'int', 'total_change' => 'int', 'is_from_pos' => 'int', 'ref_number' => 'string', 'bank_account_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime'
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
				'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
				'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'date'],
				'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
				'is_from_sales_delivery' => ['column' => $model->table.'.is_from_sales_delivery', 'alias' => 'is_from_sales_delivery', 'type' => 'int'],
				'sales_order_id' => ['column' => $model->table.'.sales_order_id', 'alias' => 'sales_order_id', 'type' => 'int'],
				'sales_order_name' => ['column' => $model->table.'.sales_order_name', 'alias' => 'sales_order_name', 'type' => 'string'],
				'customer_id' => ['column' => $model->table.'.customer_id', 'alias' => 'customer_id', 'type' => 'int'],
				'salesman_id' => ['column' => $model->table.'.salesman_id', 'alias' => 'salesman_id', 'type' => 'int'],
				'top_discount_days' => ['column' => $model->table.'.top_discount_days', 'alias' => 'top_discount_days', 'type' => 'int'],
				'top_due_days' => ['column' => $model->table.'.top_due_days', 'alias' => 'top_due_days', 'type' => 'int'],
				'top_early_discount' => ['column' => $model->table.'.top_early_discount', 'alias' => 'top_early_discount', 'type' => 'int'],
				'top_late_charge' => ['column' => $model->table.'.top_late_charge', 'alias' => 'top_late_charge', 'type' => 'int'],
				'delivery_cost' => ['column' => $model->table.'.delivery_cost', 'alias' => 'delivery_cost', 'type' => 'int'],
				'delivery_coa' => ['column' => $model->table.'.delivery_coa', 'alias' => 'delivery_coa', 'type' => 'string'],
				'other_cost' => ['column' => $model->table.'.other_cost', 'alias' => 'other_cost', 'type' => 'int'],
				'other_coa' => ['column' => $model->table.'.other_coa', 'alias' => 'other_coa', 'type' => 'string'],
				'discount_type' => ['column' => $model->table.'.discount_type', 'alias' => 'discount_type', 'type' => 'string'],
				'discount_amount' => ['column' => $model->table.'.discount_amount', 'alias' => 'discount_amount', 'type' => 'int'],
				'discount_coa' => ['column' => $model->table.'.discount_coa', 'alias' => 'discount_coa', 'type' => 'string'],
				'status' => ['column' => $model->table.'.status', 'alias' => 'status', 'type' => 'string'],
				'down_payment_amount' => ['column' => $model->table.'.down_payment_amount', 'alias' => 'down_payment_amount', 'type' => 'int'],
				'down_payment_coa' => ['column' => $model->table.'.down_payment_coa', 'alias' => 'down_payment_coa', 'type' => 'string'],
				'coa_cash' => ['column' => $model->table.'.coa_cash', 'alias' => 'coa_cash', 'type' => 'string'],
				'payment_type' => ['column' => $model->table.'.payment_type', 'alias' => 'payment_type', 'type' => 'string'],
				'total' => ['column' => $model->table.'.total', 'alias' => 'total', 'type' => 'int'],
				'tax_id' => ['column' => $model->table.'.tax_id', 'alias' => 'tax_id', 'type' => 'int'],
				'tax_amount' => ['column' => $model->table.'.tax_amount', 'alias' => 'tax_amount', 'type' => 'int'],
				'tax_percentage' => ['column' => $model->table.'.tax_percentage', 'alias' => 'tax_percentage', 'type' => 'int'],
				'tax_name' => ['column' => $model->table.'.tax_name', 'alias' => 'tax_name', 'type' => 'string'],
				'tax_coa' => ['column' => $model->table.'.tax_coa', 'alias' => 'tax_coa', 'type' => 'string'],
				'branch_id' => ['column' => $model->table.'.branch_id', 'alias' => 'branch_id', 'type' => 'int'],
				'branch_name' => ['column' => $model->table.'.branch_name', 'alias' => 'branch_name', 'type' => 'string'],
				'project_id' => ['column' => $model->table.'.project_id', 'alias' => 'project_id', 'type' => 'int'],
				'project_name' => ['column' => $model->table.'.project_name', 'alias' => 'project_name', 'type' => 'string'],
				'currency_id' => ['column' => $model->table.'.currency_id', 'alias' => 'currency_id', 'type' => 'int'],
				'currency_name' => ['column' => $model->table.'.currency_name', 'alias' => 'currency_name', 'type' => 'string'],
				'exchange_rate' => ['column' => $model->table.'.exchange_rate', 'alias' => 'exchange_rate', 'type' => 'int'],
				'warehouse_id' => ['column' => $model->table.'.warehouse_id', 'alias' => 'warehouse_id', 'type' => 'int'],
				'warehouse_name' => ['column' => $model->table.'.warehouse_name', 'alias' => 'warehouse_name', 'type' => 'string'],
				'subtotal' => ['column' => $model->table.'.subtotal', 'alias' => 'subtotal', 'type' => 'int'],
				'total_coa' => ['column' => $model->table.'.total_coa', 'alias' => 'total_coa', 'type' => 'string'],
				'is_standard' => ['column' => $model->table.'.is_standard', 'alias' => 'is_standard', 'type' => 'int'],
				'sales_quotation_id' => ['column' => $model->table.'.sales_quotation_id', 'alias' => 'sales_quotation_id', 'type' => 'int'],
				'sales_quotation_number' => ['column' => $model->table.'.sales_quotation_number', 'alias' => 'sales_quotation_number', 'type' => 'string'],
				'discount_percentage' => ['column' => $model->table.'.discount_percentage', 'alias' => 'discount_percentage', 'type' => 'int'],
				'other_income' => ['column' => $model->table.'.other_income', 'alias' => 'other_income', 'type' => 'int'],
				'other_income_coa' => ['column' => $model->table.'.other_income_coa', 'alias' => 'other_income_coa', 'type' => 'string'],
				'sales_return_status' => ['column' => $model->table.'.sales_return_status', 'alias' => 'sales_return_status', 'type' => 'string'],
				'created_by' => ['column' => $model->table.'.created_by', 'alias' => 'created_by', 'type' => 'int'],
				'total_payment' => ['column' => $model->table.'.total_payment', 'alias' => 'total_payment', 'type' => 'int'],
				'total_change' => ['column' => $model->table.'.total_change', 'alias' => 'total_change', 'type' => 'int'],
				'is_from_pos' => ['column' => $model->table.'.is_from_pos', 'alias' => 'is_from_pos', 'type' => 'int'],
				'ref_number' => ['column' => $model->table.'.ref_number', 'alias' => 'ref_number', 'type' => 'string'],
				'bank_account_id' => ['column' => $model->table.'.bank_account_id', 'alias' => 'bank_account_id', 'type' => 'int'],
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

    public static function createPOSTransaction($params)
    {   
        $validate = self::validate($params);
        if ($validate !== true) {
            return $validate;
        }

        DB::connection('pgsql_companies')->beginTransaction();

        $filename = null;
        $debit = [];
        $credit = [];
        $product_ids = [];
        $tax_ids = [];
        $deposit_histories = [];
        $tax_by_ids = [];
        $product_by_ids = [];
        $sales_invoice_details = null;
        $reward_point_applied_ids = [];
        $total_point_applied = 0;
        
        if (isset($params['reward_point_applied_ids']) && $params['reward_point_applied_ids']) {
            $reward_point_applied_ids = $params['reward_point_applied_ids'];
            unset($params['reward_point_applied_ids']);
        }

        if (isset($params['source_type']) && $params['source_type']) {
            unset($params['source_type']);
        }

        if (isset($params['total_point_applied']) && $params['total_point_applied']) {
            $total_point_applied = $params['total_point_applied']; 
            unset($params['total_point_applied']);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['deposit_histories']) && $params['deposit_histories']) {
            $deposit_histories = $params['deposit_histories'];
            unset($params['deposit_histories']);
        }

        if (isset($params['sales_invoice_details']) && $params['sales_invoice_details']) {
            $sales_invoice_details = $params['sales_invoice_details'];
            unset($params['sales_invoice_details']);
        }

        if (isset($params['exchange_rate']) && GlobalHelper::convertSeparator($params['exchange_rate'], ',') > 0) {
            $params['exchange_rate'] = GlobalHelper::convertSeparator($params['exchange_rate'], ',');
        }

        if (isset($params['total_payment']) && GlobalHelper::convertSeparator($params['total_payment'], ',') > 0) {
            $params['total_payment'] = GlobalHelper::convertSeparator($params['total_payment'], ',');
        }

        if (isset($params['total_change']) && GlobalHelper::convertSeparator($params['total_change'], ',') > 0) {
            $params['total_change'] = GlobalHelper::convertSeparator($params['total_change'], ',');
        }

        if (isset($params['total']) && GlobalHelper::convertSeparator($params['total'], ',') > 0) {
            $params['total'] = GlobalHelper::convertSeparator($params['total'], ',');
        }

        if (isset($params['discount_amount']) && GlobalHelper::convertSeparator($params['discount_amount'], ',') > 0) {
            $params['discount_amount'] = GlobalHelper::convertSeparator($params['discount_amount'], ',');

            if (!isset($params['discount_coa']) || !$params['discount_coa']) {
                $params['discount_coa'] = config('default_accounts.sales_discount');
            }

            $debit[] = [
                'coa' => $params['discount_coa'],
                // 'coa_name' => $accounting_masters[$params['discount_coa']],
                'value' => $params['discount_amount'],
                'description' => ''
            ];
        }

        if (isset($params['other_cost']) && GlobalHelper::convertSeparator($params['other_cost'], ',') > 0) {
            $params['other_cost'] = GlobalHelper::convertSeparator($params['other_cost'], ',');

            if (!isset($params['other_coa']) || !$params['other_coa']) {
                // $params['other_coa'] = config('default_accounts.other_costs');

                // SEMENTARA SEBELUM INPUTAN OTHER INCOME DI BUAT
                $params['other_coa'] = config('default_accounts.other_income');
            }

            // $debit[] = [
            //     'coa' => $params['other_coa'],
            //     'coa_name' => $accounting_masters[$params['other_coa']],
            //     'value' => $params['other_cost'],
            //     'description' => ''
            // ];

            // SEMENTARA SEBELUM INPUTAN OTHER INCOME DI BUAT
            $credit[] = [
                'coa' => $params['other_coa'],
                // 'coa_name' => $accounting_masters[$params['other_coa']],
                'value' => $params['other_cost'],
                'description' => ''
            ];
        }

        if (isset($params['other_income']) && GlobalHelper::convertSeparator($params['other_income']) > 0) {
            $params['other_income'] = GlobalHelper::convertSeparator($params['other_income']);

            if (!isset($params['other_income_coa']) || !$params['other_income_coa']) {
                $params['other_income_coa'] = config('default_accounts.other_income');
            }

            $credit[] = [
                'coa' => $params['other_income_coa'],
                // 'coa_name' => $accounting_masters[$params['other_income_coa']],
                'value' => $params['other_income'],
                'description' => ''
            ];
        }

        if (isset($params['tax_amount']) && GlobalHelper::convertSeparator($params['tax_amount'], ',') > 0) {
            $params['tax_amount'] = GlobalHelper::convertSeparator($params['tax_amount'], ',');
        }

        if (isset($params['down_payment_amount']) && $params['down_payment_amount'] > 0) {
            $params['down_payment_amount'] = GlobalHelper::convertSeparator($params['down_payment_amount'], ',');
            $down_payment_coa = $params['down_payment_coa'] ?? config('default_accounts.sales_advance');
            if ($down_payment_coa) {
                $debit[] = [
                    'coa' => $down_payment_coa,
                    // 'coa_name' => $accounting_masters[$down_payment_coa],
                    'value' => GlobalHelper::convertSeparator( $params['down_payment_amount'], ','),
                    'description' => 'Uang Muka'
                ];
            }
        }

        if (isset($params['total']) && GlobalHelper::convertSeparator($params['total']) > 0) {
            $params['total'] = GlobalHelper::convertSeparator($params['total']);
            if (!isset($params['total_coa']) || (!$params['total_coa'])) {
                $params['total_coa'] = config('default_accounts.account_receivable');
            }

            if ($params['total'] > 0) {
                if ($params['payment_type'] == 'cash') {
                    $debit[] = [
                        'coa' => $params['coa_cash'],
                        // 'coa_name' => $accounting_masters[$params['coa_cash']],
                        'value' => $params['total'],
                        'description' => null
                    ];
                } else {
                    $debit[] = [
                        'coa' => ((isset($params['total_coa']) && $params['total_coa']) ? $params['total_coa'] : config('default_accounts.account_receivable')),
                        // 'coa_name' => $accounting_masters[((isset($params['total_coa']) && $params['total_coa']) ? $params['total_coa'] : config('default_accounts.account_receivable'))],
                        'value' => $params['total'],
                        'description' => null
                    ];
                }
            }
        }

        if (isset($params['subtotal']) && GlobalHelper::convertSeparator($params['subtotal'], ',') > 0) {
            $params['subtotal'] = GlobalHelper::convertSeparator($params['subtotal'], ',');
        }

        foreach ($sales_invoice_details as $sales_invoice_detail) {
            if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                $product_ids[] = $sales_invoice_detail['product_id'];
            }

            if (isset($sales_invoice_detail['tax_id']) && $sales_invoice_detail['tax_id']) {
                $tax_ids[] = $sales_invoice_detail['tax_id'];
            }
        }

        $products = Products::select([
            'product_categories.is_control_stock as is_control_stock',
            'product_categories.inventory_coa as category_inventory_coa',
            'product_categories.cogs_coa as category_cogs_coa',
            'product_categories.sales_coa as category_sales_coa',
            'products.*'
        ])
        ->leftJoin('product_categories', 'product_categories.id', '=', 'products.product_category_id')
        ->whereIn('products.id', $product_ids)
        ->get();
        
        foreach ($products as $product) {
            $product_by_ids[$product['id']] = $product->toArray();
        }

        $taxes = Taxes::whereIn('id', $tax_ids)->get();

        foreach ($taxes as $tax) {
            $tax_by_ids[$tax['id']] = $tax->toArray();
        }
    
        $cogs_params['date'] = $params['date'];
        $cogs_params['product_ids'] = $product_ids;

        // if (isset($params['payment_type'])) {
        //     if ($params['payment_type'] == 'cash') {
        //         $params['status'] = 'paid';
        //     } else {
        //         $params['status'] = 'open';
        //     }
        // }

        $params['payment_type'] = 'cash';

        if (empty($params['status'])) {
            $params['status'] = 'draft';
        }

        // UPDATE
        if (isset($params['id']) && $params['id']) {
            $old = self::getById($params['id'])->original;

            unset($params['discount_amount_nominal']);
            $update = self::where('id', $params['id'])->update($params);

            $main_discount_percentage = 0;

            if (floatval($params['discount_amount']) && $params['discount_amount']) {
                $main_discount_percentage = $params['discount_amount'] / $params['subtotal'] * 100;
            }

            if ($update) {
                $total = 0;
                $product_histories = [];
                $cogs_data = [];

                SalesInvoiceDetails::where('sales_invoice_id', $params['id'])->delete();

                $get_cogs = ProductClosings::getCOGS($cogs_params);

                foreach ($get_cogs as $get_cogs_row) {
                    $cogs_data[$get_cogs_row['product_id']] = $get_cogs_row['cogs'];
                }

                foreach ($sales_invoice_details as $key => &$sales_invoice_detail) {
                    $cogs_price = 0;
                    $subtotal_cogs = 0;
                    $product = null;
                    $tax = null;

                    $sales_invoice_detail['sales_invoice_id'] = $params['id'];
                    $sales_invoice_detail['ref_number'] = $params['ref_number'];
                    $sales_invoice_detail['unit_price'] = GlobalHelper::convertSeparator($sales_invoice_detail['unit_price'] ?? 0, ',');
                    $sales_invoice_detail['qty'] = GlobalHelper::convertSeparator($sales_invoice_detail['qty'] ?? 0, ',');
                    $sales_invoice_detail['discount_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['discount_amount'] ?? 0, ',');
                    $sales_invoice_detail['tax_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['tax_amount'] ?? 0, ',');

                    $subtotal = $sales_invoice_detail['unit_price'] * $sales_invoice_detail['qty'];

                    if ($sales_invoice_detail['discount_type'] == 'amount') {
                        $subtotal = $subtotal - $sales_invoice_detail['discount_amount'];
                    } else {
                        $subtotal = $subtotal - ($subtotal * ($sales_invoice_detail['discount_percentage'] / 100));
                    }

                    if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                        if (isset($cogs_data[$sales_invoice_detail['product_id']])) {
                            $cogs_price = $cogs_data[$sales_invoice_detail['product_id']];
                        }
                    }

                    $subtotal_cogs = $sales_invoice_detail['qty'] * $cogs_price;

                    if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                        if (isset($product_by_ids[$sales_invoice_detail['product_id']]) && $product_by_ids[$sales_invoice_detail['product_id']]) {
                            $product = $product_by_ids[$sales_invoice_detail['product_id']];
                        }
                    }

                    if (isset($sales_invoice_detail['tax_id']) && $sales_invoice_detail['tax_id']) {
                        if (isset($tax_by_ids[$sales_invoice_detail['tax_id']]) && $tax_by_ids[$sales_invoice_detail['tax_id']]) {
                            $tax = $tax_by_ids[$sales_invoice_detail['tax_id']];
                        }
                    }

                    $total += $subtotal;

                    if ($subtotal > 0) {
                        if ($product) {
                            // Only record Sales Revenue, no COGS for unpaid credit invoices
                            if (intval($product['is_control_stock']) > 0) {
                                if (!$product['category_cogs_coa']) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Akun HPP untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun HPP nya.',
                                        'data' => null
                                    ], 400);
                                }

                                $debit[] = [
                                    'coa' => $product['category_cogs_coa'],
                                    // 'coa_name' => $accounting_masters[$product['category_cogs_coa']],
                                    'value' => $subtotal_cogs,
                                    'description' => null
                                ];

                                if (!$product['category_inventory_coa']) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Akun Persediaan untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun Persediaan nya.',
                                        'data' => null
                                    ], 400);
                                }

                                $credit[] = [
                                    'coa' => $product['category_inventory_coa'],
                                    // 'coa_name' => $accounting_masters[$product['category_inventory_coa']],
                                    'value' => $subtotal_cogs,
                                    'description' => null
                                ];

                                if (!$product['category_sales_coa']) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Akun Penjualan untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun Persediaan nya.',
                                        'data' => null
                                    ], 400);
                                }

                                $credit[] = [
                                    'coa' => ($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales')),
                                    // 'coa_name' => $accounting_masters[($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales'))],
                                    'value' => $subtotal,
                                    'description' => null
                                ];
                            } else {
                                $credit[] = [
                                    'coa' => ($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales')),
                                    // 'coa_name' => $accounting_masters[($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales'))],
                                    'value' => $subtotal,
                                    'description' => null
                                ];
                            }

                            if ($tax) {
                                $credit[] = [
                                    'coa' => $tax['sales_coa'],
                                    // 'coa_name' => $accounting_masters[$tax['sales_coa']],
                                    'value' => $main_discount_percentage > 0 ? ($sales_invoice_detail['tax_amount'] - ($sales_invoice_detail['tax_amount'] * $main_discount_percentage/100)) : $sales_invoice_detail['tax_amount'],
                                    'description' => ''
                                ];
                            }
                        } else {
                            $credit[] = [
                                'coa' => $sales_invoice_detail['coa'],
                                // 'coa_name' => $accounting_masters[$sales_invoice_detail['coa']],
                                'value' => $subtotal,
                                'description' => $sales_invoice_detail['service_name']
                            ];

                            if ($tax) {
                                $credit[] = [
                                    'coa' => $tax['sales_coa'],
                                    // 'coa_name' => $accounting_masters[$tax['sales_coa']],
                                    'value' => $main_discount_percentage > 0 ? ($sales_invoice_detail['tax_amount'] - ($sales_invoice_detail['tax_amount'] * $main_discount_percentage / 100)) : $sales_invoice_detail['tax_amount'],
                                    'description' => ''
                                ];
                            }

                            $sales_invoice_detail['is_product_unit_convert'] = 0;
                        }
                    }

                    if ($product) {
                        $unit_price = ModelDetailHelper::calculateBaseUnitPrice($sales_invoice_detail['unit_price'], $sales_invoice_detail['qty'], $sales_invoice_detail['base_qty']);
                        
                        $product_history = [
                            'model' => self::class,
                            'model_id' => $params['id'],
                            'ref_number' => $old['ref_number'],
                            'product_id' => $sales_invoice_detail['product_id'],
                            'product_code' => $sales_invoice_detail['product_code'],
                            'product_name' => $sales_invoice_detail['product_name'],
                            'qty' => $sales_invoice_detail['base_qty'],
                            'type' => 'OUT',
                            'date' => $params['date'],
                            'project_id' => $params['project_id'] ?? null,
                            'project_name' => $params['project_name'] ?? '',
                            'unit_id' => $sales_invoice_detail['base_unit_id'] ?? null,
                            'unit_name' => $sales_invoice_detail['unit_name'] ?? '',
                            'unit_price' => $unit_price ?? 0,
                            'cogs_price' => $cogs_price ?? null,
                            'branch_id' => $params['branch_id'] ?? null,
                            'branch_name' => $params['branch_name'] ?? null,
                            'currency_id' => $params['currency_id'] ?? null,
                            'currency_name' => $params['currency_name'] ?? '',
                            'exchange_rate' => $params['exchange_rate'] ?? 1,
                            'warehouse_id' => $params['warehouse_id'] ?? null,
                            'warehouse_name' => $params['warehouse_name'] ?? '',
                        ];
                    }

                    if (isset($sales_invoice_detail['id']) && $sales_invoice_detail['id']) {
                        $sales_invoice_detail['deleted_at'] = null;
                        unset($sales_invoice_detail['created_at']);
                        SalesInvoiceDetails::onlyTrashed()->where('id', $sales_invoice_detail['id'])->update($sales_invoice_detail);
                        unset($sales_invoice_details[$key]);
                        
                        if ($product) {
                            $product_history['deleted_at'] = null;
                            unset($product_history['created_at']);
                            $product_history['update_by_model'] = true;
                            ProductHistories::createOrUpdateService($product_history);
                        }
                    } else {
                        if ($product) {
                            unset($product_history['update_by_model']);
                            $product_histories[] = $product_history;
                        }
                    }

                    unset($sales_invoice_detail['id']);
                }
                
                ProductHistories::bulkCreate($product_histories);
                SalesInvoiceDetails::insert($sales_invoice_details);

                $customer = Contacts::where('id', $params['customer_id'])->withTrashed()->first();

                if ($params['payment_type'] == 'credit') {
                    $accounting_params['description'] = 'Penjualan Kredit dari Pengiriman Barang '.$customer['name'];
                } else {
                    $accounting_params['description'] = 'Penjualan Cash dari Supplier '.$customer['name'];
                }

                $accounting_params['ref_number'] = $old['ref_number'];
                $accounting_params['date'] = $params['date'];
                $accounting_params['total'] = $params['total'];
                $accounting_params['currency_id'] = $params['currency_id'] ?? 0;
                $accounting_params['currency_name'] = $params['currency_name'] ?? '';
                $accounting_params['branch_id'] = $params['branch_id'] ?? 0;
                $accounting_params['branch_name'] = $params['branch_name'] ?? '';
                $accounting_params['project_id'] = $params['project_id'] ?? 0;
                $accounting_params['project_name'] = $params['project_name'] ?? '';
                $accounting_params['exchange_rate'] = $params['exchange_rate'] ?? 1;
                // $accounting_params['created_by'] = config('user.id');
                $accounting_params['model_id'] = $params['id'];
                $accounting_params['model'] = self::class;

                AccountingJournals::writeAccounting($debit, $credit, $accounting_params, $accounting_params['model']);

                // DepositHistories::where('model_id', $params['id'])->where('model', 'SalesInvoices')->where('type', 'out')->delete();

                // if ($deposit_histories) {
                //     foreach($deposit_histories as  &$deposit_history){
                //         $deposit_history['date'] = $params['date'];
                //         $deposit_history['model'] = 'SalesInvoices';
                //         $deposit_history['model_id'] = $update->id;
                //         $deposit_history['type'] = 'out';
                //         $deposit_history['created_at'] = date('Y-m-d');
                //         $deposit_history['updated_at'] = date('Y-m-d');
                //     }
    
                //     DepositHistories::insert($deposit_histories);
                // }

                if (empty($params['is_draft'])) {
                    $bonus_points = ContactGroups::generateRewardPoints($customer->contact_group_id, [
                        'total_purchase' => $params['total'],
                        'chart_items' => $sales_invoice_details,
                    ]);
        
                    if ($bonus_points > 0) {
                        PointHistories::create([
                            'model' => 'SalesInvoices',
                            'model_id' => $update->id,
                            'contact_id' => $customer->id,
                            'point' => $bonus_points,
                            'note' => '',
                            'date' => now(),
                            'type' => 'in'
                        ]);
                    }

                    if ($total_point_applied > 0) {
                        PointHistories::create([
                            'model' => 'SalesInvoices',
                            'model_id' => $update->id,
                            'contact_id' => $customer->id,
                            'point' => $total_point_applied,
                            'note' => '',
                            'date' => now(),
                            'type' => 'out'
                        ]);
                    }
                }
            }

            DB::connection('pgsql_companies')->commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data',
                'data' => self::getById($params['id'])->original
            ]);
        }

        // CREATE
        $params['is_from_pos'] = 1;

        $save = self::create($params);

        $main_discount_percentage = 0;

        if (floatval($params['discount_amount']) && $params['discount_amount']) {
            $main_discount_percentage = $params['discount_amount'] / $params['subtotal'] * 100;
        }
        
        if ($save) {
            $total = 0;
            $product_histories = [];
            // $product_by_ids = [];
            $cogs_data = [];

            // AMBIL NILAI HPP
            $get_cogs = ProductClosings::getCOGS($cogs_params);
    
            foreach ($get_cogs as $get_cogs_row) {
                $cogs_data[$get_cogs_row['product_id']] = $get_cogs_row['cogs'];
            }
            
            foreach ($sales_invoice_details as &$sales_invoice_detail) {
                $cogs_price = 0;
                $subtotal_cogs = 0;
                $product = null;
                $tax = null;

                $sales_invoice_detail['sales_invoice_id'] = $save->id;
                $sales_invoice_detail['ref_number'] = $params['ref_number'];
                $sales_invoice_detail['qty'] = GlobalHelper::convertSeparator($sales_invoice_detail['qty'] ?? 0, ',');
                $sales_invoice_detail['unit_price'] = GlobalHelper::convertSeparator($sales_invoice_detail['unit_price'] ?? 0, ',');
                $sales_invoice_detail['discount_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['discount_amount'] ?? 0, ',');
                $sales_invoice_detail['tax_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['tax_amount'] ?? 0, ',');

                $subtotal = $sales_invoice_detail['unit_price'] * $sales_invoice_detail['qty'];

                if ($sales_invoice_detail['discount_type'] == 'amount') {
                    $subtotal = $subtotal - $sales_invoice_detail['discount_amount'];
                } else {
                    $subtotal = $subtotal - ($subtotal * ($sales_invoice_detail['discount_percentage'] / 100));
                }

                if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                    if (isset($cogs_data[$sales_invoice_detail['product_id']])) {
                        $cogs_price = $cogs_data[$sales_invoice_detail['product_id']];
                    }
                }

                if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                    if (isset($product_by_ids[$sales_invoice_detail['product_id']]) && $product_by_ids[$sales_invoice_detail['product_id']]) {
                        $product = $product_by_ids[$sales_invoice_detail['product_id']];
                    }
                }

                if (isset($sales_invoice_detail['tax_id']) && $sales_invoice_detail['tax_id']) {
                    if (isset($tax_by_ids[$sales_invoice_detail['tax_id']]) && $tax_by_ids[$sales_invoice_detail['tax_id']]) {
                        $tax = $tax_by_ids[$sales_invoice_detail['tax_id']];
                    }
                }

                $subtotal_cogs = $sales_invoice_detail['qty'] * $cogs_price;
                $total += $subtotal;

                if ($subtotal > 0) {
                    if ($product) {
                        // Only record Sales Revenue, no COGS for unpaid credit invoices
                        if (intval($product['is_control_stock']) > 0) {
                            if (!$product['category_cogs_coa']) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Akun HPP untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun HPP nya.',
                                    'data' => null
                                ], 400);
                            }

                            $debit[] = [
                                'coa' => $product['category_cogs_coa'],
                                // 'coa_name' => $accounting_masters[$product['category_cogs_coa']],
                                'value' => $subtotal_cogs,
                                'description' => null
                            ];

                            if (!$product['category_inventory_coa']) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Akun Persediaan untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun Persediaan nya.',
                                    'data' => null
                                ], 400);
                            }

                            $credit[] = [
                                'coa' => $product['category_inventory_coa'],
                                // 'coa_name' => $accounting_masters[$product['category_inventory_coa']],
                                'value' => $subtotal_cogs,
                                'description' => null
                            ];

                            if (!$product['category_sales_coa']) {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Akun Penjualan untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun Persediaan nya.',
                                    'data' => null
                                ], 400);
                            }

                            $credit[] = [
                                'coa' => ($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales')),
                                // 'coa_name' => $accounting_masters[($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales'))],
                                'value' => $subtotal,
                                'description' => null
                            ];
                        } else {
                            $credit[] = [
                                'coa' => ($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales')),
                                // 'coa_name' => $accounting_masters[($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales'))],
                                'value' => $subtotal,
                                'description' => null
                            ];
                        }

                        if ($tax) {
                            $credit[] = [
                                'coa' => $tax['sales_coa'],
                                // 'coa_name' => $accounting_masters[$tax['sales_coa']],
                                'value' => $main_discount_percentage > 0 ? ($sales_invoice_detail['tax_amount'] - ($sales_invoice_detail['tax_amount'] * $main_discount_percentage/100)) : $sales_invoice_detail['tax_amount'],
                                'description' => ''
                            ];
                        }
                    } else {
                        $sales_invoice_detail['is_product_unit_convert'] = 0;

                        $credit[] = [
                            'coa' => $sales_invoice_detail['coa'],
                            // 'coa_name' => $accounting_masters[$sales_invoice_detail['coa']],
                            'value' => $subtotal,
                            'description' => $sales_invoice_detail['service_name']
                        ];

                        if ($tax) {
                            $credit[] = [
                                'coa' => $tax['sales_coa'],
                                // 'coa_name' => $accounting_masters[$tax['sales_coa']],
                                'value' => $main_discount_percentage > 0 ? ($sales_invoice_detail['tax_amount'] - ($sales_invoice_detail['tax_amount'] * $main_discount_percentage/100)) : $sales_invoice_detail['tax_amount'],
                                'description' => ''
                            ];
                        }
                    }
                }

                if ($params['status'] != 'draft') {
                    if ($product) {
                        $product_histories[] = [
                            'model' => self::class,
                            'model_id' => $save->id,
                            'ref_number' => $params['ref_number'],
                            'product_id' => $sales_invoice_detail['product_id'],
                            'product_code' => $sales_invoice_detail['product_code'],
                            'product_name' => $sales_invoice_detail['product_name'],
                            'qty' => $sales_invoice_detail['qty'],
                            'date' => $params['date'],
                            'type' => 'OUT',
                            'project_id' => $params['project_id'] ?? null,
                            'project_name' => $params['project_name'] ?? '',
                            'unit_id' => $sales_invoice_detail['unit_id'] ?? null,
                            'unit_name' => $sales_invoice_detail['unit_name'] ?? '',
                            'unit_price' => $sales_invoice_detail['unit_price'] ?? null,
                            'cogs_price' => $cogs_price ?? null,
                            'branch_id' => $params['branch_id'] ?? null,
                            'branch_name' => $params['branch_name'] ?? null,
                            'currency_id' => $params['currency_id'] ?? null,
                            'currency_name' => $params['currency_name'] ?? '',
                            'exchange_rate' => $params['exchange_rate'] ?? 1,
                            'warehouse_id' => $params['warehouse_id'] ?? null,
                            'warehouse_name' => $params['warehouse_name'] ?? '',
                            'product_sku_id' => $params['product_sku_id'] ?? null
                        ];
                    }
                }
            }

            ProductHistories::bulkCreate($product_histories);

            SalesInvoiceDetails::insert($sales_invoice_details);

            if ($params['status'] != 'draft') {
                $customer = Contacts::where('id', $params['customer_id'])->withTrashed()->first();
    
                $accounting_params['number'] = AutoNumberHelper::initGenerateNumber('JU', $params['date']);
                $accounting_params['ref_number'] = $params['ref_number'];
                $accounting_params['date'] = $params['date'];
                $accounting_params['total'] = $params['total'];
                $accounting_params['currency_id'] = $params['currency_id'] ?? 0;
                $accounting_params['currency_name'] = $params['currency_name'] ?? '';
                $accounting_params['branch_id'] = $params['branch_id'] ?? 0;
                $accounting_params['branch_name'] = $params['branch_name'] ?? '';
                $accounting_params['project_id'] = $params['project_id'] ?? 0;
                $accounting_params['project_name'] = $params['project_name'] ?? '';
                $accounting_params['exchange_rate'] = $params['exchange_rate'] ?? 1;
                // $accounting_params['created_by'] = config('user.id');
                $accounting_params['description'] = 'Invoice Penjualan ke ' . $customer['name'];
                $accounting_params['model_id'] = $save['id'];
                $accounting_params['model'] = self::class;
    
                AccountingJournals::writeAccounting($debit, $credit, $accounting_params, $accounting_params['model']);
                    
                // if (!empty($deposit_histories)) {
                //     foreach($deposit_histories as  &$deposit_history){
                //         $deposit_history['date'] = $params['date'];
                //         $deposit_history['model'] = 'SalesInvoices';
                //         $deposit_history['model_id'] = $save->id;
                //         $deposit_history['type'] = 'out';
                //         $deposit_history['created_at'] = date('Y-m-d');
                //         $deposit_history['updated_at'] = date('Y-m-d');
                //     }
        
                //     DepositHistories::insert($deposit_histories);
                // }
            
                if (empty($params['is_draft'])) {
                    $bonus_points = ContactGroups::generateRewardPoints($customer->contact_group_id, [
                        'total_purchase' => $params['total'],
                        'chart_items' => $sales_invoice_details,
                    ]);
        
                    if ($bonus_points > 0) {
                        PointHistories::create([
                            'model' => 'SalesInvoices',
                            'model_id' => $save->id,
                            'contact_id' => $customer->id,
                            'point' => $bonus_points,
                            'note' => '',
                            'date' => now(),
                            'type' => 'in'
                        ]);
                    }

                    // $reward_points = RewardPoints::get();
                    // $reward_points_by_id = [];
                    // $point_histories_out_count = 0;

                    // foreach ($reward_points as $key => $value) {
                    //     $reward_points_by_id[$value['id']] = $value;
                    // }

                    // if (count($reward_point_applied_ids) > 0) {
                    //     foreach ($reward_point_applied_ids as $key => $rpa_id) {
                    //         if (isset($reward_points_by_id[$rpa_id])) {
                    //             $data = $reward_points_by_id[$rpa_id];
                    //             $point_histories_out_count += floatval($data['total_point']);
                    //         }
                    //     }
                    // }

                    if ($total_point_applied > 0) {
                        PointHistories::create([
                            'model' => 'SalesInvoices',
                            'model_id' => $save->id,
                            'contact_id' => $customer->id,
                            'point' => $total_point_applied,
                            'note' => '',
                            'date' => now(),
                            'type' => 'out'
                        ]);
                    }
                }
            }
        }

        DB::connection('pgsql_companies')->commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Added Data',
            'data' => self::getById($save->id)->original
        ], 200);
    }

    private static function createFromSync($invoice)
    {
        DB::connection('pgsql_companies')->beginTransaction();

        $debit = [];
        $credit = [];
        $product_ids = [];
        $tax_ids = [];
        $tax_by_ids = [];
        $product_by_ids = [];
        $sales_invoice_details = null;

        if (isset($invoice['sales_invoice_details']) && $invoice['sales_invoice_details']) {
            $sales_invoice_details = $invoice['sales_invoice_details'];
            unset($invoice['sales_invoice_details']);
        }

        try {
            $data = collect($invoice)->only((new self)->getFillable())->toArray();

            unset($data['number']);
            unset($data['id']);

            if (isset($data['discount_amount']) && GlobalHelper::convertSeparator($data['discount_amount'], ',') > 0) {
                $data['discount_amount'] = GlobalHelper::convertSeparator($data['discount_amount'], ',');

                if (!isset($data['discount_coa']) || !$data['discount_coa']) {
                    $data['discount_coa'] = config('default_accounts.sales_discount');
                }

                $debit[] = [
                    'coa' => $data['discount_coa'],
                    'value' => $data['discount_amount'],
                    'description' => ''
                ];
            }

            if (isset($data['other_cost']) && GlobalHelper::convertSeparator($data['other_cost'], ',') > 0) {
                $data['other_cost'] = GlobalHelper::convertSeparator($data['other_cost'], ',');

                if (!isset($data['other_coa']) || !$data['other_coa']) {
                    $data['other_coa'] = config('default_accounts.other_income');
                }

                $credit[] = [
                    'coa' => $data['other_coa'],
                    'value' => $data['other_cost'],
                    'description' => ''
                ];
            }

            if (isset($data['other_income']) && GlobalHelper::convertSeparator($data['other_income']) > 0) {
                $data['other_income'] = GlobalHelper::convertSeparator($data['other_income']);

                if (!isset($data['other_income_coa']) || !$data['other_income_coa']) {
                    $data['other_income_coa'] = config('default_accounts.other_income');
                }

                $credit[] = [
                    'coa' => $data['other_income_coa'],
                    'value' => $data['other_income'],
                    'description' => ''
                ];
            }

            if (isset($data['tax_amount']) && GlobalHelper::convertSeparator($data['tax_amount'], ',') > 0) {
                $data['tax_amount'] = GlobalHelper::convertSeparator($data['tax_amount'], ',');
            }

            if (isset($data['down_payment_amount']) && $data['down_payment_amount'] > 0) {
                $data['down_payment_amount'] = GlobalHelper::convertSeparator($data['down_payment_amount'], ',');
                $down_payment_coa = $data['down_payment_coa'] ?? config('default_accounts.purchase_advance');
                
                if ($down_payment_coa) {
                    $debit[] = [
                        'coa' => $down_payment_coa,
                        'value' => $data['down_payment_amount'],
                        'description' => 'Uang Muka'
                    ];
                }
            }

            if (isset($data['total']) && GlobalHelper::convertSeparator($data['total']) > 0) {
                $data['total'] = GlobalHelper::convertSeparator($data['total']);

                if ($data['total'] > 0) {
                    if ($data['payment_type'] == 'cash') {
                        $debit[] = [
                            'coa' => $data['coa_cash'],
                            'value' => $data['total'],
                            'description' => null
                        ];
                    } else {
                        $debit[] = [
                            'coa' => ((isset($data['total_coa']) && $data['total_coa']) ? $data['total_coa'] : config('default_accounts.account_receivable')),
                            'value' => $data['total'],
                            'description' => null
                        ];
                    }
                }
            }

            if (isset($data['subtotal']) && GlobalHelper::convertSeparator($data['subtotal'], ',') > 0) {
                $data['subtotal'] = GlobalHelper::convertSeparator($data['subtotal'], ',');
            }

            foreach ($sales_invoice_details as $sales_invoice_detail) {
                if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                    $product_ids[] = $sales_invoice_detail['product_id'];
                }

                if (isset($sales_invoice_detail['tax_id']) && $sales_invoice_detail['tax_id']) {
                    $tax_ids[] = $sales_invoice_detail['tax_id'];
                }
            }

            $products = Products::select([
                'product_categories.is_control_stock as is_control_stock',
                'product_categories.inventory_coa as category_inventory_coa',
                'product_categories.cogs_coa as category_cogs_coa',
                'product_categories.sales_coa as category_sales_coa',
                'products.*'
            ])
            ->leftJoin('product_categories', 'product_categories.id', '=', 'products.product_category_id')
            ->whereIn('products.id', $product_ids)
            ->get();
            
            foreach ($products as $product) {
                $product_by_ids[$product['id']] = $product->toArray();
            }

            $taxes = Taxes::whereIn('id', $tax_ids)->get();

            foreach ($taxes as $tax) {
                $tax_by_ids[$tax['id']] = $tax->toArray();
            }
        
            $cogs_params['date'] = $data['date'];
            $cogs_params['product_ids'] = $product_ids;

            $data['number'] = AutoNumberHelper::initGenerateNumber('SI', $data['date']);
            $data['is_from_pos'] = 1;

            $save = self::create($data);

            $main_discount_percentage = 0;

            if (floatval($data['discount_amount']) && $data['discount_amount']) {
                $main_discount_percentage = $data['discount_amount'] / $data['subtotal'] * 100;
            }

            /*
            |--------------------------------------------------------------------------
            | SALES INVOICE DETAILS
            |--------------------------------------------------------------------------
            */
            if ($save) {
                $total = 0;
                $product_histories = [];
                $cogs_data = [];

                $get_cogs = ProductClosings::getCOGS($cogs_params);
        
                foreach ($get_cogs as $get_cogs_row) {
                    $cogs_data[$get_cogs_row['product_id']] = $get_cogs_row['cogs'];
                }

                foreach ($sales_invoice_details as &$sales_invoice_detail) {
                    $cogs_price = 0;
                    $subtotal_cogs = 0;
                    $product = null;
                    $tax = null;

                    $sales_invoice_detail['sales_invoice_id'] = $save->id;
                    $sales_invoice_detail['ref_number'] = $save->ref_number;

                    $subtotal = $sales_invoice_detail['unit_price'] * $sales_invoice_detail['qty'];

                    if ($sales_invoice_detail['discount_type'] == 'amount') {
                        $subtotal = $subtotal - $sales_invoice_detail['discount_amount'];
                    } else {
                        $subtotal = $subtotal - ($subtotal * ($sales_invoice_detail['discount_percentage'] / 100));
                    }

                    if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                        if (isset($cogs_data[$sales_invoice_detail['product_id']])) {
                            $cogs_price = $cogs_data[$sales_invoice_detail['product_id']];
                        }
                    }

                    if (isset($sales_invoice_detail['product_id']) && $sales_invoice_detail['product_id']) {
                        if (isset($product_by_ids[$sales_invoice_detail['product_id']]) && $product_by_ids[$sales_invoice_detail['product_id']]) {
                            $product = $product_by_ids[$sales_invoice_detail['product_id']];
                        }
                    }

                    if (isset($sales_invoice_detail['tax_id']) && $sales_invoice_detail['tax_id']) {
                        if (isset($tax_by_ids[$sales_invoice_detail['tax_id']]) && $tax_by_ids[$sales_invoice_detail['tax_id']]) {
                            $tax = $tax_by_ids[$sales_invoice_detail['tax_id']];
                        }
                    }

                    $subtotal_cogs = $sales_invoice_detail['qty'] * $cogs_price;
                    $total += $subtotal;

                    if ($subtotal > 0) {
                        if ($product) {
                            if (intval($product['is_control_stock']) > 0) {
                                if (!$product['category_cogs_coa']) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Akun HPP untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun HPP nya.',
                                        'data' => null
                                    ], 400);
                                }

                                $debit[] = [
                                    'coa' => $product['category_cogs_coa'],
                                    'value' => $subtotal_cogs,
                                    'description' => null
                                ];

                                if (!$product['category_inventory_coa']) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Akun Persediaan untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun Persediaan nya.',
                                        'data' => null
                                    ], 400);
                                }

                                $credit[] = [
                                    'coa' => $product['category_inventory_coa'],
                                    'value' => $subtotal_cogs,
                                    'description' => null
                                ];

                                if (!$product['category_sales_coa']) {
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Akun Penjualan untuk produk ' . $product['name'] . ' belum diset, harap cek kategori produk, dan cek Akun Persediaan nya.',
                                        'data' => null
                                    ], 400);
                                }

                                $credit[] = [
                                    'coa' => ($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales')),
                                    'value' => $subtotal,
                                    'description' => null
                                ];
                            } else {
                                $credit[] = [
                                    'coa' => ($product['category_sales_coa'] ? $product['category_sales_coa'] : config('default_accounts.sales')),
                                    'value' => $subtotal,
                                    'description' => null
                                ];
                            }

                            if ($tax) {
                                $credit[] = [
                                    'coa' => $tax['sales_coa'],
                                    'value' => $main_discount_percentage > 0 ? ($sales_invoice_detail['tax_amount'] - ($sales_invoice_detail['tax_amount'] * $main_discount_percentage/100)) : $sales_invoice_detail['tax_amount'],
                                    'description' => ''
                                ];
                            }
                        }

                        $product_histories[] = [
                            'model' => self::class,
                            'model_id' => $save->id,
                            'ref_number' => $data['ref_number'],
                            'product_id' => $sales_invoice_detail['product_id'],
                            'product_code' => $sales_invoice_detail['product_code'],
                            'product_name' => $sales_invoice_detail['product_name'],
                            'qty' => $sales_invoice_detail['qty'],
                            'date' => $data['date'],
                            'type' => 'OUT',
                            'project_id' => $data['project_id'] ?? null,
                            'project_name' => $data['project_name'] ?? '',
                            'unit_id' => $sales_invoice_detail['unit_id'] ?? null,
                            'unit_name' => $sales_invoice_detail['unit_name'] ?? '',
                            'unit_price' => $sales_invoice_detail['unit_price'] ?? null,
                            'cogs_price' => $cogs_price ?? null,
                            'branch_id' => $data['branch_id'] ?? null,
                            'branch_name' => $data['branch_name'] ?? null,
                            'currency_id' => $data['currency_id'] ?? null,
                            'currency_name' => $data['currency_name'] ?? '',
                            'exchange_rate' => $data['exchange_rate'] ?? 1,
                            'warehouse_id' => $data['warehouse_id'] ?? null,
                            'warehouse_name' => $data['warehouse_name'] ?? '',
                            'product_sku_id' => $data['product_sku_id'] ?? null
                        ];
                    }
                }

                ProductHistories::bulkCreate($product_histories);
                SalesInvoiceDetails::insert($sales_invoice_details);
    
                $customer = Contacts::where('id', $data['customer_id'])->withTrashed()->first();
    
                $accounting_params['number'] = AutoNumberHelper::initGenerateNumber('JU', $data['date']);
                $accounting_params['ref_number'] = $data['ref_number'];
                $accounting_params['date'] = $data['date'];
                $accounting_params['total'] = $data['total'];
                $accounting_params['currency_id'] = $data['currency_id'] ?? 0;
                $accounting_params['currency_name'] = $data['currency_name'] ?? '';
                $accounting_params['branch_id'] = $data['branch_id'] ?? 0;
                $accounting_params['branch_name'] = $data['branch_name'] ?? '';
                $accounting_params['project_id'] = $data['project_id'] ?? 0;
                $accounting_params['project_name'] = $data['project_name'] ?? '';
                $accounting_params['exchange_rate'] = $data['exchange_rate'] ?? 1;
                // $accounting_params['created_by'] = config('user.id');
                $accounting_params['description'] = 'Invoice Penjualan ke ' . $customer['name'];
                $accounting_params['model_id'] = $save['id'];
                $accounting_params['model'] = self::class;
    
                AccountingJournals::writeAccounting($debit, $credit, $accounting_params, $accounting_params['model']);
                    
            }

            DB::connection('pgsql_companies')->commit();
            return $save;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function syncFromLocal($params)
    {
        $results = [];
        $errors = [];

        DB::connection('pgsql_companies')->statement("SELECT SETVAL('sales_invoices_id_seq', COALESCE((SELECT MAX(id) + 1 FROM sales_invoices), 1))");
        DB::connection('pgsql_companies')->statement("SELECT SETVAL('sales_invoice_details_id_seq', COALESCE((SELECT MAX(id) + 1 FROM sales_invoice_details), 1))");
        DB::connection('pgsql_companies')->statement("SELECT SETVAL('point_histories_id_seq', COALESCE((SELECT MAX(id) + 1 FROM point_histories), 1))");
        
        foreach ($params as $invoice) {
            DB::connection('pgsql_companies')->beginTransaction();

            try {
                if (empty($invoice['date']) || empty($invoice['customer_id'])) {
                    throw new \Exception('Data invoice tidak valid');
                }

                $new_invoice = self::createFromSync($invoice);

                if (!empty($invoice['point_histories'])) {
                    PointHistories::createHistoriesFromSync($new_invoice->id, $invoice['point_histories']);
                }

                DB::connection('pgsql_companies')->commit();

                $results[] = [
                    'local_id' => $invoice['id'] ?? null,
                    'number'   => $new_invoice->number
                ];

            } catch (\Exception $e) {
                DB::rollBack();
                $errors[] = [
                    'local_id' => $invoice['id'] ?? null,
                    'error' => $e->getMessage()
                ];
            }
        }

        return [
            'status' => empty($errors) ? 'success' : 'partial',
            'data' => $results,
            'errors' => $errors
        ];
    }
}
