<?php
namespace App\Http\Controllers\Finance;

use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Finance\ResourceController as BaseController;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierBillItemRepository;
use App\Repositories\Eloquent\SupplierBillItemInfoRepository;
use App\Repositories\Eloquent\SupplierRepository;
use App\Repositories\Eloquent\SupplierBalanceRecordRepository;
use Auth;
use Illuminate\Http\Request;
use App\Models\SupplierBill;

/**
 * Resource controller class for user.
 */
class SupplierBillResourceController extends BaseController
{


    public function __construct(
        SupplierBillRepository $supplierBillRepository,
        SupplierBillItemRepository $supplierBillItemRepository,
        SupplierBillItemInfoRepository $supplierBillItemInfoRepository,
        AirportRepository $airportRepository,
        AirlineRepository $airlineRepository,
        SupplierRepository $supplierRepository,
        SupplierBalanceRecordRepository $supplierBalanceRecordRepository
    )
    {
        parent::__construct();
        $this->repository = $supplierBillRepository;
        $this->airportRepository = $airportRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierBillItemRepository = $supplierBillItemRepository;
        $this->supplierBillItemInfoRepository = $supplierBillItemInfoRepository;
        $this->supplierBalanceRecordRepository = $supplierBalanceRecordRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $bills = $this->repository;

            $bills = $bills
                ->orderBy('invoice_date','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($bills as $key => $bill)
            {
                $bill->status_button = $bill->getStatusTwoLevelButton($bill->status);
                $bill->pay_status_button = $bill->getPayStatusButton($bill->pay_status);
            }
            return $this->response
                ->success()
                ->count($bills->total())
                ->data($bills->toArray()['data'])
                ->output();
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $airlines = $this->airlineRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('supplier_bill.title'))
            ->data(compact('airports','airlines'))
            ->view('supplier_bill.index')
            ->output();
    }

    public function show(Request $request,SupplierBill $supplier_bill)
    {
        if ($supplier_bill->exists) {
            $view = 'supplier_bill.show';
        } else {
            $view = 'supplier_bill.new';
        }
        $airport = $this->airportRepository->orderBy('id','desc')->first();
        $airline = $this->airlineRepository->orderBy('id','desc')->first();

        $supplier_bill_items = $this->supplierBillItemRepository->where('supplier_bill_id',$supplier_bill->id)->orderBy('flight_date','asc')->get();

        return $this->response->title(trans('app.view') . ' ' . trans('supplier_bill.name'))
            ->data(compact('airport','airline','supplier_bill','supplier_bill_items'))
            ->view($view)
            ->output();
    }


    public function pay(Request $request,SupplierBill $supplier_bill)
    {

        return $this->response->title(trans('app.pay') . ' ' . trans('supplier_bill.name'))
            ->view('supplier_bill.pay')
            ->data(compact('supplier_bill'))
            ->output();
    }
    public function paySubmit(Request $request,SupplierBill $supplier_bill)
    {
        try {
            $attributes = $request->all();
            $attributes['pay_status'] = 'paid';

            $this->supplierBalanceRecordRepository->pay(
                $supplier_bill->supplier_id,
                $attributes['paid_total'],
                [
                    'out_trade_no' => $supplier_bill->sn,
                    'trade_type' => 'PAY_SUPPLIER_BILL',
                    'description' => '支付供应商账单',
                ]
            );

            $supplier_bill->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier_bill.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_bill'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_bill/pay/'.$supplier_bill->id))
                ->redirect();
        }
    }

}