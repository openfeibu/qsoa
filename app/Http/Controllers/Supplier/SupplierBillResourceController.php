<?php
namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportBalanceRecordRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\SupplierBillItemInfoRepository;
use App\Repositories\Eloquent\SupplierBillItemRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierBillTemplateFieldRepository;
use App\Repositories\Eloquent\SupplierRepository;
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
        SupplierBillTemplateFieldRepository $supplierBillTemplateFieldRepository,
        AirportRepository $airportRepository,
        AirlineRepository $airlineRepository,
        SupplierRepository $supplierRepository,
        AirportBalanceRecordRepository $airportBalanceRecordRepository
    )
    {
        parent::__construct();
        $this->repository = $supplierBillRepository;
        $this->airportRepository = $airportRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierBillItemRepository = $supplierBillItemRepository;
        $this->supplierBillItemInfoRepository = $supplierBillItemInfoRepository;
        $this->supplierBillTemplateFieldRepository = $supplierBillTemplateFieldRepository;
        $this->airportBalanceRecordRepository = $airportBalanceRecordRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $bills = $this->repository
                ->where(['supplier_id' => Auth::user()->supplier_id]);

            $bills = $bills
                ->orderBy('invoice_date','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($bills as $key => $bill)
            {
                $bill->status_button = $bill->getStatusOneLevelButton($bill->status);
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
        $airport = $this->airportRepository->find($supplier_bill->airport_id);
        $airline = $this->airlineRepository->find($supplier_bill->airline_id);

        $supplier_bill_items = $this->supplierBillItemRepository->where('supplier_bill_id',$supplier_bill->id)->orderBy('flight_date','asc')->get();

        $fields = [];
        foreach ($supplier_bill_items as $key => $supplier_bill_item)
        {
            $supplier_bill_item->infos = $supplier_bill_item->infos->toArray();
            if(!$fields)
            {
                foreach ($supplier_bill_item->infos as $key => $info)
                {
                    $fields[] = [
                        'id' => $info['supplier_bill_template_field_id'],
                        'field' => $info['field'],
                        'field_comment' => $info['field_comment'],
                    ];
                }
            }

        }

        return $this->response->title(trans('app.view') . ' ' . trans('supplier_bill.name'))
            ->data(compact('airport','airline','supplier_bill','supplier_bill_items','fields'))
            ->view($view)
            ->output();
    }

    /**
     * Show the form for creating a new user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $supplier_bill = $this->repository->newInstance([]);

        $data =  $request->all();
        $ids = $data['supplier_bill_item_ids'];

        $fields = $this->supplierBillTemplateFieldRepository->fields(Auth::user()->supplier_id);

        $supplier_bill_items = $this->supplierBillItemRepository->whereIn('id',$ids)->orderBy('flight_date','asc')->get();
        $airport_ids = $airline_ids = [];

        $total = 0;
        foreach ($supplier_bill_items as $key => $supplier_bill_item)
        {
            $supplier_bill_item->infos = $supplier_bill_item->infos->toArray();
            $airport_ids[] = $supplier_bill_item->airport_id;
            $airline_ids[] = $supplier_bill_item->airline_id;
            $total += $supplier_bill_item->total;
        }

        $airport_ids = array_unique($airport_ids);
        $airline_ids = array_unique($airline_ids);

        if(count($airport_ids) > 1 || count($airline_ids) > 1)
        {
            return $this->response->message(trans('supplier_bill.message.create_repeat_airline_or_airport'))
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }
        $airport_id = $airport_ids[0];
        $airline_id = $airline_ids[0];

        $airport = $this->airportRepository->find($airport_id);
        $airline = $this->airlineRepository->find($airline_id);

        return $this->response->title(trans('app.new') . ' ' . trans('supplier_bill.name'))
            ->view('supplier_bill.create')
            ->data(compact('airport','airline','supplier_bill','supplier_bill_items','fields','total'))
            ->output();

    }

    /**
     * Create new user.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['sn'] = build_order_sn('sp');
            $attributes['supplier_id'] = Auth::user()->supplier_id;
            $attributes['supplier_name'] = $this->supplierRepository->find($attributes['supplier_id'],['name'])->name;
            $attributes['airport_name'] = $this->airportRepository->find($attributes['airport_id'],['name'])->name;
            $attributes['airline_name'] = $this->airlineRepository->find($attributes['airline_id'],['name'])->name;
            $supplier_bill = $this->repository->create($attributes);
            $this->repository->operation([
                'id' => $supplier_bill->id,
                'status' => 'new'
            ]);
            foreach ($attributes['supplier_bill_item_ids'] as $key => $supplier_bill_item_id)
            {
                $this->supplierBillItemRepository->update([
                    'supplier_bill_id' => $supplier_bill->id
                ],$supplier_bill_item_id);
            }

            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier_bill.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('supplier_bill'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }

    }

    /**
     * Update the user.
     *
     * @param Request $request
     * @param SupplierBill   $supplier_bill
     *
     * @return Response
     */
    public function update(Request $request, SupplierBill $supplier_bill)
    {
        try {
            $attributes = $request->all();
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
                ->url(guard_url('supplier_bill/' . $supplier_bill->id))
                ->redirect();
        }
    }

    /**
     * @param Request $request
     * @param SupplierBill $supplier_bill
     * @return mixed
     */
    public function destroy(Request $request, SupplierBill $supplier_bill)
    {
        try {
            $this->repository->operation([
                'id' => $supplier_bill->id,
                'status' => 'invalid'
            ]);
            $supplier_bill->forceDelete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_bill.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('supplier_bill'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_bill'))
                ->redirect();
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_bill.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_bill'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_bill'))
                ->redirect();
        }
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


            $this->airportBalanceRecordRepository->pay(
                $supplier_bill->airport_id,
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