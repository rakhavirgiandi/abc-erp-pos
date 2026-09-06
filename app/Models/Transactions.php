<?php

namespace App\Models;

use App\Helpers\AutoNumberHelper;
use App\Helpers\DuitkuService;
use App\Helpers\IpaymuService;
use App\Helpers\ModelHelper;
use App\Models\Companies;
use App\Models\Editions;
use App\Models\Invoices;
use App\Models\Periods;
use App\Models\Subscriptions;
use App\Models\TransactionDetails;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * @property string company_id
 * @property string number
 * @property string date
 * @property string discount_percentage
 * @property string referral_code
 * @property string voucher_code
 * @property string note
 * @property int    discount_amount
 * @property int    total
 * @property int    deleted_at
 * @property int    created_at
 * @property int    updated_at
 */
class Transactions extends Model
{
    use SoftDeletes;
    protected $connection = 'pgsql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'transactions';

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
        'company_id',
		'number',
		'date',
		'discount_amount',
		'discount_percentage',
		'referral_code',
		'voucher_code',
		'total',
		'note',
		'deleted_at',
		'created_at',
		'updated_at',
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
        'company_id' => 'string', 'number' => 'string', 'date' => 'string', 'discount_amount' => 'int', 'discount_percentage' => 'string', 'referral_code' => 'string', 'voucher_code' => 'string', 'total' => 'int', 'note' => 'string', 'deleted_at' => 'datetime', 'created_at' => 'datetime', 'updated_at' => 'datetime'
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

    public function invoice()
    {
        return $this->hasOne(Invoices::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetails::class, 'transaction_id');
    }

    public static function mapSchema($params = [], $user = [])
    {
        $model = new self;

        return [
            'field' => [
                'id' => ['column' => $model->table.'.id', 'alias' => 'id', 'type' => 'string'],
				'company_id' => ['column' => $model->table.'.company_id', 'alias' => 'company_id', 'type' => 'string'],
				'number' => ['column' => $model->table.'.number', 'alias' => 'number', 'type' => 'string'],
				'date' => ['column' => $model->table.'.date', 'alias' => 'date', 'type' => 'string'],
				'discount_amount' => ['column' => $model->table.'.discount_amount', 'alias' => 'discount_amount', 'type' => 'int'],
				'discount_percentage' => ['column' => $model->table.'.discount_percentage', 'alias' => 'discount_percentage', 'type' => 'string'],
				'referral_code' => ['column' => $model->table.'.referral_code', 'alias' => 'referral_code', 'type' => 'string'],
				'voucher_code' => ['column' => $model->table.'.voucher_code', 'alias' => 'voucher_code', 'type' => 'string'],
				'total' => ['column' => $model->table.'.total', 'alias' => 'total', 'type' => 'int'],
				'note' => ['column' => $model->table.'.note', 'alias' => 'note', 'type' => 'string'],
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
        $db->with('invoice')->with('transactionDetails');
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

        $db->with('invoice');
        
        $results = ModelHelper::generateAllResults($schema, $params, $request, $db, $append);

        return response()->json($results);
    }

    public static function createOrUpdate($params, $method, $request)
    {
        DB::connection('pgsql')->beginTransaction();

        $user = auth()->guard('api')->user();
        // $ipaymu = new IpaymuService();
        $duitku = new DuitkuService();
        $filename = null;

        if (isset($params['_token']) && $params['_token']) {
            unset($params['_token']);
        }

        if (isset($params['id']) && $params['id']) {
            $old = self::getById($params['id'])->original;

            $update = self::where('id', $params['id'])->update($params);

            DB::connection('pgsql')->commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Succesfully Updated Data'
            ]);
        }

        $company = Companies::where('id', $params['company_id'])->first();
        $edition = Editions::where('id', $params['edition_id'])->first();
        $period = Periods::where('id', $params['period_id'])->first();

        $total = $period['price'] - $period['discount_amount'];

        $data['id'] = Str::orderedUuid()->toString();
        $data['company_id'] = $params['company_id'];
        $data['number'] = AutoNumberHelper::initGenerateNumber('TR-');
        $data['date'] = date('Y-m-d H:i:s');
        $data['discount_amount'] = $period['discount_amount'];
        $data['discount_percentage'] = $period['discount_percentage'];
        $data['referral_code'] = '';
        $data['voucher_code'] = '';
        $data['total'] = $total;
        $data['note'] = 'Biaya Berlangganan '.$company['name'];

        $save = self::create($data);

        if ($save) {
            $transaction_detail['id'] = Str::orderedUuid()->toString();
            $transaction_detail['transaction_id'] = $data['id'];
            $transaction_detail['edition_id'] = $params['edition_id'];
            $transaction_detail['period_id'] = $params['period_id'];
            $transaction_detail['product_name'] = $edition['name'];
            $transaction_detail['qty'] = 1;
            $transaction_detail['unit_price'] = $total;
            $transaction_detail['discount_amount'] = $period['discount_amount'];
            $transaction_detail['discount_percentage'] = $period['discount_percentage'];

            TransactionDetails::create($transaction_detail);

            $invoice['id'] = Str::orderedUuid()->toString();
            $invoice['number'] = AutoNumberHelper::initGenerateNumber('INV-');
            $invoice['transactions_id'] = $data['id'];
            $invoice['total'] = $total;
            $invoice['callback_url'] = 'https://app.simandor.id/api/duitku_callback';
            $invoice['payment_code'] = $params['payment_code'];
            $invoice['payment_via'] = $params['payment_via'];

            // $ipaymu_params['name'] = $user->name;
            // $ipaymu_params['email'] = $user->email;
            // $ipaymu_params['phone'] = $user->phone;
            // $ipaymu_params['amount'] = $total;
            // $ipaymu_params['paymentMethod'] = 'va';
            // $ipaymu_params['paymentChannel'] = $params['payment_code'];
            // $ipaymu_params['expired'] = '1';
            // $ipaymu_params['expiredType'] = 'hours';
            // $ipaymu_params['referenceId'] = $invoice['number'];
            // $ipaymu_params['notifyUrl']  = 'https://app.simandor.id/api/ipaymu_params_callback';
            // $ipaymu_params['product'] = [$edition['name']];
            // $ipaymu_params['qty'] = ['1'];
            // $ipaymu_params['price'] = [$total];
            
            // $send_inquiry = $ipaymu->sendPaymentInquiry($ipaymu_params);

            $start_date = date('Y-m-d H:i:s');

            $duitku_params['total'] = $total;
            $duitku_params['payment_method'] = $params['payment_code'];
            $duitku_params['invoice_number'] = $invoice['number'];
            $duitku_params['email'] = $user->email;
            $duitku_params['phone'] = $user->phone;
            $duitku_params['name'] = $user->name;
            $duitku_params['address'] = 'Jalan Generated From Sistem';
            $duitku_params['expiry_in_minutes'] = 1440;
            $duitku_params['items'] = [
                [
                    'name' => $edition['name']. ' ' .$period['name'],
                    'price' => $total,
                    'quantity' => 1,
                ]
            ];

            $expired_date = date('Y-m-d H:i:s', strtotime('+'.$duitku_params['expiry_in_minutes'].' minutes', strtotime($start_date)));

            $send_inquiry = $duitku->sendPaymentInquiry($duitku_params);

            $invoice['payment_data_req'] = $duitku_params;
            $invoice['payment_data_res'] = $send_inquiry;
            $invoice['start_at'] = $start_date;
            $invoice['expired_at'] = $expired_date;

            Invoices::create($invoice);

            $subscription = Subscriptions::where('company_id', $params['company_id'])->first();

            Subscriptions::where('company_id', $params['company_id'])->update([
                'status' => 'Awaiting Payment'
            ]);
            
            SubscriptionHistories::create([
                'id' => Str::orderedUuid()->toString(),
                'subscription_id' => $subscription->id,
                'company_id' => $params['company_id'],
                'start_at' => date('Y-m-d H:i:s'),
                'finish_at' => $expired_date,
                'status' => 'Awaiting Payment',
                'referral_code' => '',
                'type' => 'subscribe',
            ]);
        }

        DB::connection('pgsql')->commit();
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
}
