<?php

namespace App\Models;

use App\Helpers\ModelHelper;
use App\Models\SubscriptionHistories;
use App\Models\Subscriptions;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property string number
 * @property string transactions_id
 * @property string callback_url
 * @property string callback_status
 * @property string callback_error
 * @property string payment_data_req
 * @property string payment_data_res
 * @property int    total
 * @property int    deleted_at
 * @property int    created_at
 * @property int    updated_at
 */
class Invoices extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'invoices';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    
    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'number',
		'transactions_id',
		'total',
		'callback_url',
		'callback_status',
		'callback_error',
		'payment_data_req',
		'payment_data_res',
        'payment_code',
        'payment_via',
		'deleted_at',
		'created_at',
		'updated_at',
        'start_at',
        'expired_at',
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
        'number' => 'string', 'transactions_id' => 'string', 'total' => 'int', 'callback_url' => 'string', 'callback_status' => 'string', 'callback_error' => 'string', 'payment_data_req' => 'array', 'payment_data_res' => 'array', 'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at', 'created_at', 'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = true;

    public $incrementing = false;

    // Scopes...

    // Functions ...

    // Relations ...

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'string'],
				'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
				'transactions_id' => ['column' => $model->table.'.transactions_id', 'alias' => 'transactions_id', 'type' => 'string'],
				'total' => ['column' => $model->table.'.total', 'alias' => 'total', 'type' => 'int'],
				'callback_url' => ['column' => $model->table.'.callback_url', 'alias' => 'callback_url', 'type' => 'string'],
				'callback_status' => ['column' => $model->table.'.callback_status', 'alias' => 'callback_status', 'type' => 'string'],
				'callback_error' => ['column' => $model->table.'.callback_error', 'alias' => 'callback_error', 'type' => 'string'],
                'payment_code' => ['column' => $model->table.'.payment_code', 'alias' => 'payment_code', 'type' => 'int'],
                'payment_via' => ['column' => $model->table.'.payment_via', 'alias' => 'payment_via', 'type' => 'int'],
				'payment_data_req' => ['column' => $model->table.'.payment_data_req', 'alias' => 'payment_data_req', 'type' => 'string'],
				'payment_data_res' => ['column' => $model->table.'.payment_data_res', 'alias' => 'payment_data_res', 'type' => 'string'],
                'start_at' => ['column' => $model->table.'.start_at', 'alias' => 'start_at', 'type' => 'string'],
                'expired_at' => ['column' => $model->table.'.expired_at', 'alias' => 'expired_at', 'type' => 'string'],
				'deleted_at' => ['column' => $model->table.'.deleted_at', 'alias' => 'deleted_at', 'type' => 'date'],
				'created_at' => ['column' => $model->table.'.created_at', 'alias' => 'created_at', 'type' => 'date'],
				'updated_at' => ['column' => $model->table.'.updated_at', 'alias' => 'updated_at', 'type' => 'date'],
            ],
            'join' => [

            ],
            'where' => [

            ]
        ];
    }

    public static function datatables($start, $length, $order, $dir, $search, $filter = [])
    {
        $totalData = self::count();

        $qry = ModelHelper::select(self::mapSchema()['field'], null, __CLASS__);
        ModelHelper::join(self::mapSchema()['join'], null, $qry);
        
        if (count($filter) > 0) {

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
            foreach (array_values(self::mapSchema()['field']) as $key => $val) {
                if ($key < 1) {
                    $qry->whereRaw('('.$val['column'].'::varchar(255) ILIKE \'%'.$search.'%\'');
                } else if (count(array_values(self::mapSchema()['field'])) == ($key + 1)) {
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

        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__);
        ModelHelper::join(self::mapSchema()['join'], $request, $db);

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
        
        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__)->where($models->table.'.id', $id);
        
        ModelHelper::join(self::mapSchema()['join'], $request, $db);
        
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

        $db = ModelHelper::select(self::mapSchema()['field'], $request, __CLASS__);
        ModelHelper::join(self::mapSchema()['join'], $request, $db);

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
                'message' => 'Succesfully Updated Data'
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
        self::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Succesfully Deleted Data'
        ]);
    }

    public static function duitkuCallback($params, $method, $request)
    {
        DB::beginTransaction();

        $now_date = date('Y-m-d H:i:s');
        $invoice_number = $params['merchantOrderId'];
        $status = $params['resultCode'];
        $duitku_ref = $params['reference'];
        $settlement_date = $params['settlementDate'];

        $invoice = self::where('number', $invoice_number)->first();
        $transaction = Transactions::where('id', $invoice['transactions_id'])->with('transactionDetails')->first();
        $company_id = $transaction['company_id'];

        foreach($transaction['transactionDetails'] as $transaction_detail) {
            $period = Periods::where('id', $transaction_detail['period_id'])->first();
            $expired_date = date('Y-m-d', strtotime($now_date. ' + '.$period['day'].' days'));

            if ($status == '00') {
                $subscription = Subscriptions::where('company_id', $company_id)->first();

                if ($subscription['status'] == 'Subscribe') {
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment has already PAID'
                    ]);
                }

                Subscriptions::where('company_id', $company_id)->update([
                    'status' => 'Subscribe',
                    'start_at' => $now_date,
                    'finish_at' => $expired_date
                ]);

                SubscriptionHistories::create([
                    'id' => Str::orderedUuid()->toString(),
                    'subscription_id' => $subscription['id'],
                    'company_id' => $company_id,
                    'start_at' => $now_date,
                    'finish_at' => $expired_date,
                    'status' => 'Subscribe',
                    'referral_code' => '',
                    'type' => 'Subscribe',
                ]);

                self::where('number', $invoice_number)->update([
                    'callback_status' => 'PAID',
                    'callback_error' => $duitku_ref,
                ]);
            } else {
                self::where('number', $invoice_number)->update([
                    'callback_status' => 'FAILED'
                ]);
            }
        }

        DB::commit();
        return response()->json([
            'status' => 'success',
            'message' => 'Callback succesfully received'
        ]);
    }
}
