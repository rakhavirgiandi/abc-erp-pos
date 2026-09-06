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
use Illuminate\Validation\Rule;

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
        'is_need_sync',
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
        'number' => 'string', 'date' => 'string', 'description' => 'string', 'is_from_sales_delivery' => 'int', 'sales_order_id' => 'int', 'sales_order_name' => 'string', 'customer_id' => 'int', 'salesman_id' => 'int', 'top_discount_days' => 'int', 'top_due_days' => 'int', 'top_early_discount' => 'int', 'top_late_charge' => 'int', 'delivery_cost' => 'int', 'delivery_coa' => 'string', 'other_cost' => 'int', 'other_coa' => 'string', 'discount_type' => 'string', 'discount_amount' => 'int', 'discount_coa' => 'string', 'status' => 'string', 'down_payment_amount' => 'int', 'down_payment_coa' => 'string', 'coa_cash' => 'string', 'payment_type' => 'string', 'total' => 'float', 'tax_id' => 'int', 'tax_amount' => 'int', 'tax_percentage' => 'int', 'tax_name' => 'string', 'tax_coa' => 'string', 'branch_id' => 'int', 'branch_name' => 'string', 'project_id' => 'int', 'project_name' => 'string', 'currency_id' => 'int', 'currency_name' => 'string', 'exchange_rate' => 'int', 'warehouse_id' => 'int', 'warehouse_name' => 'string', 'subtotal' => 'float', 'total_coa' => 'string', 'is_standard' => 'int', 'sales_quotation_id' => 'int', 'sales_quotation_number' => 'string', 'discount_percentage' => 'int', 'other_income' => 'int', 'other_income_coa' => 'string', 'sales_return_status' => 'string', 'created_by' => 'int', 'total_payment' => 'float', 'total_change' => 'float', 'is_from_pos' => 'int', 'ref_number' => 'string', 'bank_account_id' => 'int', 'created_at' => 'datetime', 'updated_at' => 'datetime', 'deleted_at' => 'datetime', 'is_need_sync' => 'int'
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
    public function sales_invoice_details()
    {
        return $this->hasMany(SalesInvoiceDetails::class, 'sales_invoice_id', 'id');
    }
    public function product_histories()
    {
        return $this->hasMany(ProductHistories::class, 'model_id', 'id')->where('model', self::class);
    }

    public function point_histories()
    {
        return $this->hasMany(PointHistories::class, 'model_id', 'id')->where('model', 'SalesInvoices');
    }

    public function accounting_journals()
    {
        return $this->hasMany(AccountingJournals::class, 'model_id', 'id')->where('model', self::class);
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'int'],
				'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
				'ref_number' => ['column' => $model->table.'.ref_number', 'alias' => 'ref_number', 'type' => 'string'],
				'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'date'],
				'description' => ['column' => $model->table.'.description', 'alias' => 'description', 'type' => 'string'],
				'is_from_sales_delivery' => ['column' => $model->table.'.is_from_sales_delivery', 'alias' => 'is_from_sales_delivery', 'type' => 'int'],
				'sales_order_id' => ['column' => $model->table.'.sales_order_id', 'alias' => 'sales_order_id', 'type' => 'int'],
				'sales_order_name' => ['column' => $model->table.'.sales_order_name', 'alias' => 'sales_order_name', 'type' => 'string'],
				'customer_id' => ['column' => $model->table.'.customer_id', 'alias' => 'customer_id', 'type' => 'int'],
				'customer_name' => ['column' =>'contact_customer.name', 'alias' => 'customer_name', 'type' => 'string'],
				'customer_address' => ['column' =>'contact_customer.address', 'alias' => 'customer_address', 'type' => 'string'],
				'contact_group_id' => ['column' =>'contact_customer.contact_group_id', 'alias' => 'contact_group_id', 'type' => 'int'],
                'salesman_id' => ['column' => $model->table.'.salesman_id', 'alias' => 'salesman_id', 'type' => 'int'],
                'salesman_name' => ['column' =>'contact_salesman.name', 'alias' => 'salesman_name', 'type' => 'string'],
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
                'currency_code' => ['column' => 'currencies.code', 'alias' => 'currency_code', 'type' => 'string'],
				'exchange_rate' => ['column' => $model->table.'.exchange_rate', 'alias' => 'exchange_rate', 'type' => 'int'],
				'warehouse_id' => ['column' => $model->table.'.warehouse_id', 'alias' => 'warehouse_id', 'type' => 'int'],
				'warehouse_name' => ['column' => $model->table.'.warehouse_name', 'alias' => 'warehouse_name', 'type' => 'string'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
				'subtotal' => ['column' => $model->table.'.subtotal', 'alias' => 'subtotal', 'type' => 'int'],
				'total_coa' => ['column' => $model->table.'.total_coa', 'alias' => 'total_coa', 'type' => 'string'],
				'is_standard' => ['column' => $model->table.'.is_standard', 'alias' => 'is_standard', 'type' => 'int'],
				'sales_quotation_id' => ['column' => $model->table.'.sales_quotation_id', 'alias' => 'sales_quotation_id', 'type' => 'int'],
				'sales_quotation_number' => ['column' => $model->table.'.sales_quotation_number', 'alias' => 'sales_quotation_number', 'type' => 'string'],
				'discount_percentage' => ['column' => $model->table.'.discount_percentage', 'alias' => 'discount_percentage', 'type' => 'int'],
				'other_income' => ['column' => $model->table.'.other_income', 'alias' => 'other_income', 'type' => 'int'],
				'other_income_coa' => ['column' => $model->table.'.other_income_coa', 'alias' => 'other_income_coa', 'type' => 'string'],
				'sales_return_status' => ['column' => $model->table.'.sales_return_status', 'alias' => 'sales_return_status', 'type' => 'string'],
                'discount_accounting_code' => ['column' => 'discount_coa_master.accounting_code', 'alias' => 'discount_accounting_code', 'type' => 'string'],
                'discount_coa_name' => ['column' => 'discount_coa_master.name', 'alias' => 'discount_coa_name', 'type' => 'string'],
                'other_accounting_code' => ['column' => 'other_coa_master.accounting_code', 'alias' => 'other_accounting_code', 'type' => 'string'],
                'other_coa_name' => ['column' => 'other_coa_master.name', 'alias' => 'other_coa_name', 'type' => 'string'],
                'cash_accounting_code' => ['column' => 'cash_coa_master.accounting_code', 'alias' => 'cash_accounting_code', 'type' => 'string'],
                'cash_coa_name' => ['column' => 'cash_coa_master.name', 'alias' => 'cash_coa_name', 'type' => 'string'],
                'down_payment_accounting_code' => ['column' => 'down_payment_coa_master.accounting_code', 'alias' => 'down_payment_accounting_code', 'type' => 'string'],
                'down_payment_coa_name' => ['column' => 'down_payment_coa_master.name', 'alias' => 'down_payment_coa_name', 'type' => 'string'],
                'total_accounting_code' => ['column' => 'total_coa_master.accounting_code', 'alias' => 'total_accounting_code', 'type' => 'string'],
                'total_coa_name' => ['column' => 'total_coa_master.name', 'alias' => 'total_coa_name', 'type' => 'string'],
                'created_by' => ['column' => $model->table.'.created_by', 'alias' => 'created_by', 'type' => 'int'],
                'created_by_name' => ['column' => 'creator.name', 'alias' => 'created_by_name', 'type' => 'string'],
                'total_payment' => ['column' => $model->table.'.total_payment', 'alias' => 'total_payment', 'type' => 'int'],
                'total_change' => ['column' => $model->table.'.total_change', 'alias' => 'total_change', 'type' => 'int'],
                'is_from_pos' => ['column' => $model->table.'.is_from_pos', 'alias' => 'is_from_pos', 'type' => 'int'],
                'bank_account_id' => ['column' => $model->table.'.bank_account_id', 'alias' => 'bank_account_id', 'type' => 'int'],
                'is_need_sync' => ['column' => $model->table.'.is_need_sync', 'alias' => 'is_need_sync', 'type' => 'int'],
            ],
            'join' => [
                ['table' => 'contacts as contact_customer', 'type' => 'left', 'on' => ['contact_customer.id', '=', $model->table . '.customer_id']],
                ['table' => 'contacts as contact_salesman', 'type' => 'left', 'on' => ['contact_salesman.id', '=', $model->table . '.customer_id']],
                ['table' => 'currencies', 'type' => 'left', 'on' => ['currencies.id', '=', $model->table . '.currency_id']],
                ['table' => 'users as creator', 'type' => 'left', 'on' => ['creator.id', '=', $model->table . '.created_by']],
                ['table' => 'accounting_masters as discount_coa_master', 'type' => 'left', 'on' => ['discount_coa_master.coa', '=', $model->table . '.discount_coa']],
                ['table' => 'accounting_masters as other_coa_master', 'type' => 'left', 'on' => ['other_coa_master.coa', '=', $model->table . '.other_coa']],
                ['table' => 'accounting_masters as cash_coa_master', 'type' => 'left', 'on' => ['cash_coa_master.coa', '=', $model->table . '.coa_cash']],
                ['table' => 'accounting_masters as down_payment_coa_master', 'type' => 'left', 'on' => ['down_payment_coa_master.coa', '=', $model->table . '.down_payment_coa']],
                ['table' => 'accounting_masters as total_coa_master', 'type' => 'left', 'on' => ['total_coa_master.coa', '=', $model->table . '.total_coa']],
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
        $with_delivery = false;
        $with_product_detail = false;
        $with_customer_detail = false;

        if (isset($params['with_delivery']) && $params['with_delivery'] == 'true') {
            $with_delivery = true;
            unset($params['with_delivery']);
        }

        $schema = self::mapSchema();

        if (isset($params['with_product_detail']) && $params['with_product_detail'] == true) {
            $with_product_detail = true;
            unset($params['with_product_detail']);
        }

        if (isset($params['with_customer_detail']) && $params['with_customer_detail'] == true) {
            $with_customer_detail = true;
            unset($params['with_customer_detail']);
        }
        
        $db = ModelHelper::select($schema['field'], $request, __CLASS__)->where($models->table.'.id', $id);

        $db->with(['sales_invoice_details']);

        if ($with_product_detail) {

            $db->with(['sales_invoice_details.product_detail' => function ($q) {
                $q->with(['multi_prices' => function ($mp_qry) {
                    $mp_qry->leftJoin('contact_groups', 'contact_groups.id', '=', 'product_multi_prices.contact_group_id')
                    ->leftJoin('branches', 'branches.id', '=', 'product_multi_prices.branch_id')
                    ->select(
                      'product_multi_prices.*',
                      'branches.name as branch_name',
                      'branches.code as branch_code',
                      'contact_groups.name as contact_group_name',
                    );
                }]);

                $q->with(['unit_conversions' => function ($un_qry) {
                    $un_qry->leftJoin('units as form_unit', 'form_unit.id', '=', 'product_unit_conversions.from_unit_id')
                    ->leftJoin('units as to_unit', 'to_unit.id', '=', 'product_unit_conversions.to_unit_id')
                    ->select(
                      'product_unit_conversions.*',
                      'form_unit.name as from_unit_name',
                      'to_unit.name as to_unit_name',
                    );
                }]);

                
                $q->with(['media']);

                $q->with(['product_skus.product_sku_variants' => function($sku_qry) {
                    $sku_qry->leftJoin('variants', 'variants.id', '=', 'product_sku_variants.variant_id')
                      ->leftJoin('variant_options', 'variant_options.id', '=', 'product_sku_variants.option_id')
                      ->select(
                          'product_sku_variants.*',
                          'variants.name as variant_name',
                          'variant_options.value as option_value'
                      );
                }]);
            }]);

            $db->with(['sales_invoice_details.unit_detail.conversions']);
            $db->with(['sales_invoice_details.product_sku_detail']);
        }
        ModelHelper::join($schema['join'], $request, $db);
        $data = $db->first();

        if ($with_customer_detail) {
            $data->customer_detail = Contacts::getById($data->customer_id)->original;
        }

        if (!$data) {
            return response()->json(null);
        }

        return response()->json($data);
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

    public static function validate($params)
    {
        $rules = [
            'date'        => ['required', 'date'],
            'customer_id' => ['required', 'integer'],
            'warehouse_id' => ['required', 'integer'],
            'branch_id' => ['required', 'integer'],
            'sales_invoice_details' => ['required', 'array', 'min:1'],
        ];

        if (isset($params['is_from_pos']) && $params['is_from_pos'] == 0) {
            $rules['number'] = ['required', Rule::unique('sales_invoices', 'number')->withoutTrashed()];
        }

        if (isset($params['is_from_sales_delivery']) && $params['is_from_sales_delivery']) {
            $rules['sales_invoice_details.*.sales_delivery_id'] = ['required'];
        } else {
            $rules['sales_invoice_details.*.product_id'] = ['nullable', 'integer', 'required_without:sales_invoice_details.*.service_name'];
            $rules['sales_invoice_details.*.service_name'] = ['nullable', 'string', 'required_without:sales_invoice_details.*.product_id'];
            $rules['sales_invoice_details.*.coa'] = ['nullable', 'required_with:sales_invoice_details.*.service_name',];
            $rules['sales_invoice_details.*.qty'] = ['required', 'regex:/^(\d{1,3}(\,\d{3})|(\d+))(\.\d+)?$/'];
            $rules['sales_invoice_details.*.unit_price'] = ['required', 'min:0'];
        }

        if ($params['payment_type'] == 'credit') {
            $rules['top_due_days'] = 'required';
        } else {
            $rules['coa_cash'] = 'required';
        }

        $messages = [
            'number.unique' => __('validation.number_unique'),
            'number.required' => __('validation.number_required'),
            'date.required' => __('validation.date_required'),
            'customer_id.required' => __('validation.customer_required'),
            'warehouse_id.required' => __('validation.warehouse_required'),
            'branch_id.required' => __('validation.branch_required'),
            'top_due_days.required' =>  __('validation.top_due_days_required'),
            'coa_cash.required' =>  __('validation.coa_cash_required'),
            'sales_invoice_details.required' => __('validation.details_required'),
            'sales_invoice_details.min' => __('validation.details_min'),

            'sales_invoice_details.*.product_id.required_without' => __('validation.product_or_service'),
            'sales_invoice_details.*.service_name.required_without' => __('validation.product_or_service'),
            'sales_invoice_details.*.coa.required_with' => __('validation.coa_required'),
            'sales_invoice_details.*.qty.required' => __('validation.qty_required'),
            'sales_invoice_details.*.unit_price.required' => __('validation.unit_price_required'),
            'sales_invoice_details.*.sales_delivery_id.required' => __('validation.sales_delivery_required'),
        ];

        if (isset($params['id']) && $params['id']) {
            if (isset($params['is_from_pos']) && $params['is_from_pos'] == 0) {
                $rules['number'] = ['required',
                    Rule::unique('sales_invoices', 'number')->ignore($params['id'])->withoutTrashed(),
                ];
            }
        }

        $validator = Validator::make($params, $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors()->all()
            ], 422);
        }

        return true;
    }

    public static function createPOSTransaction($params)
    {   
        $validate = self::validate($params);
        if ($validate !== true) {
            return $validate;
        }

        DB::connection('pgsql_companies')->beginTransaction();

        $filename = null;
        $sales_invoice_details = null;
        $reward_point_applied_ids = [];
        $total_point_applied = 0;
        $params['is_need_sync'] = 1;
        
        if (isset($params['reward_point_applied_ids']) && $params['reward_point_applied_ids']) {
            $reward_point_applied_ids = $params['reward_point_applied_ids'];
            unset($params['reward_point_applied_ids']);
        }

        if (isset($params['source_type']) && $params['source_type']) {
            unset($params['source_type']);
        }

        if (isset($params['total_point_applied'])) {
            $total_point_applied = $params['total_point_applied']; 
            unset($params['total_point_applied']);
        }

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
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
        }

        if (isset($params['other_cost']) && GlobalHelper::convertSeparator($params['other_cost'], ',') > 0) {
            $params['other_cost'] = GlobalHelper::convertSeparator($params['other_cost'], ',');

            if (!isset($params['other_coa']) || !$params['other_coa']) {
                // $params['other_coa'] = config('default_accounts.other_costs');

                // SEMENTARA SEBELUM INPUTAN OTHER INCOME DI BUAT
                $params['other_coa'] = config('default_accounts.other_income');
            }
        }

        if (isset($params['other_income']) && GlobalHelper::convertSeparator($params['other_income']) > 0) {
            $params['other_income'] = GlobalHelper::convertSeparator($params['other_income']);

            if (!isset($params['other_income_coa']) || !$params['other_income_coa']) {
                $params['other_income_coa'] = config('default_accounts.other_income');
            }
        }

        if (isset($params['tax_amount']) && GlobalHelper::convertSeparator($params['tax_amount'], ',') > 0) {
            $params['tax_amount'] = GlobalHelper::convertSeparator($params['tax_amount'], ',');
        }

        if (isset($params['down_payment_amount']) && $params['down_payment_amount'] > 0) {
            $params['down_payment_amount'] = GlobalHelper::convertSeparator($params['down_payment_amount'], ',');
            $down_payment_coa = $params['down_payment_coa'] ?? config('default_accounts.sales_advance');
        }

        if (isset($params['total']) && GlobalHelper::convertSeparator($params['total']) > 0) {
            $params['total'] = GlobalHelper::convertSeparator($params['total']);
            if (!isset($params['total_coa']) || (!$params['total_coa'])) {
                $params['total_coa'] = config('default_accounts.account_receivable');
            }
        }

        if (isset($params['subtotal']) && GlobalHelper::convertSeparator($params['subtotal'], ',') > 0) {
            $params['subtotal'] = GlobalHelper::convertSeparator($params['subtotal'], ',');
        }

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

        $generate_reward_point_items = []; 

        // UPDATE
        if (isset($params['id']) && $params['id']) {
            $old = self::getById($params['id'])->original;

            $update = self::where('id', $params['id'])->update($params);

            if ($update) {
                SalesInvoiceDetails::where('sales_invoice_id', $params['id'])->delete();

                foreach ($sales_invoice_details as $key => &$sales_invoice_detail) {
                    $sales_invoice_detail['sales_invoice_id'] = $params['id'];
                    $sales_invoice_detail['ref_number'] = $params['ref_number'];
                    $sales_invoice_detail['unit_price'] = GlobalHelper::convertSeparator($sales_invoice_detail['unit_price'] ?? 0, ',');
                    $sales_invoice_detail['qty'] = GlobalHelper::convertSeparator($sales_invoice_detail['qty'] ?? 0, ',');
                    $sales_invoice_detail['discount_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['discount_amount'] ?? 0, ',');
                    $sales_invoice_detail['tax_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['tax_amount'] ?? 0, ',');

                    if (isset($sales_invoice_detail['id']) && $sales_invoice_detail['id']) {
                        $sales_invoice_detail['deleted_at'] = null;
                        unset($sales_invoice_detail['created_at']);
                        SalesInvoiceDetails::onlyTrashed()->where('id', $sales_invoice_detail['id'])->update($sales_invoice_detail);
                        unset($sales_invoice_details[$key]);   
                    }

                    $generate_reward_point_items[] = [
                        "price" => GlobalHelper::convertSeparator($sales_invoice_detail['unit_price'] ?? 0, ','),
                        "unit_id" => $sales_invoice_detail["unit_id"],
                        "product_id" => $sales_invoice_detail["product_id"],
                        "qty" => GlobalHelper::convertSeparator($sales_invoice_detail['qty'] ?? 0, ',')
                    ];

                    unset($sales_invoice_detail['id']);
                }
                
                SalesInvoiceDetails::insert($sales_invoice_details);

                $customer = Contacts::where('id', $params['customer_id'])->withTrashed()->first();

                if (empty($params['is_draft'])) {
                    $bonus_points = ContactGroups::generateRewardPoints($customer->contact_group_id, [
                        'total_purchase' => $params['total'],
                        'chart_items' => $generate_reward_point_items,
                    ]);

                    PointHistories::where('model', '=', 'SalesInvoices')->where('model_id', '=', $params['id'])->where('contact_id', '=', $customer->id)->delete();
        
                    if ($bonus_points > 0) {
                        PointHistories::create([
                            'model' => 'SalesInvoices',
                            'model_id' => $params['id'],
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
                            'model_id' => $params['id'],
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
        
        if ($save) {
            foreach ($sales_invoice_details as &$sales_invoice_detail) {
                $sales_invoice_detail['sales_invoice_id'] = $save->id;
                $sales_invoice_detail['ref_number'] = $params['ref_number'];
                $sales_invoice_detail['qty'] = GlobalHelper::convertSeparator($sales_invoice_detail['qty'] ?? 0, ',');
                $sales_invoice_detail['unit_price'] = GlobalHelper::convertSeparator($sales_invoice_detail['unit_price'] ?? 0, ',');
                $sales_invoice_detail['discount_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['discount_amount'] ?? 0, ',');
                $sales_invoice_detail['tax_amount'] = GlobalHelper::convertSeparator($sales_invoice_detail['tax_amount'] ?? 0, ',');

                $generate_reward_point_items[] = [
                    "price" => GlobalHelper::convertSeparator($sales_invoice_detail['unit_price'] ?? 0, ','),
                    "unit_id" => $sales_invoice_detail["unit_id"],
                    "product_id" => $sales_invoice_detail["product_id"],
                    "qty" => GlobalHelper::convertSeparator($sales_invoice_detail['qty'] ?? 0, ',')
                ];
            }

            SalesInvoiceDetails::insert($sales_invoice_details);

            if ($params['status'] != 'draft') {
                $customer = Contacts::where('id', $params['customer_id'])->withTrashed()->first();

                if (empty($params['is_draft'])) {
                    $bonus_points = ContactGroups::generateRewardPoints($customer->contact_group_id, [
                        'total_purchase' => $params['total'],
                        'chart_items' => $generate_reward_point_items,
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
}
