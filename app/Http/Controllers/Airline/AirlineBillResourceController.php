<?php
namespace App\Http\Controllers\Airline;

use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Airline\ResourceController as BaseController;
use App\Models\AirlineBillItem;
use App\Repositories\Eloquent\AirlineBillItemRepository;
use App\Repositories\Eloquent\AirlineBillRepository;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierRepository;
use App\Repositories\Eloquent\SupplierBillItemInfoRepository;
use App\Repositories\Eloquent\SupplierBillItemRepository;
use Auth;
use Illuminate\Http\Request;
use App\Models\AirlineBill;

/**
 * Resource controller class for user.
 */
class AirlineBillResourceController extends BaseController
{


    public function __construct(
        SupplierBillRepository $supplierBillRepository,
        SupplierBillItemRepository $supplierBillItemRepository,
        SupplierBillItemInfoRepository $supplierBillItemInfoRepository,
        AirlineBillRepository $airlineBillRepository,
        AirlineBillItemRepository $airlineBillItemRepository,
        AirportRepository $airportRepository,
        AirlineRepository $airlineRepository,
        SupplierRepository $supplierRepository
    )
    {
        parent::__construct();
        $this->repository = $airlineBillRepository;
        $this->supplierBillRepository = $supplierBillRepository;
        $this->airportRepository = $airportRepository;
        $this->airlineBillItemRepository = $airlineBillItemRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierBillItemRepository = $supplierBillItemRepository;
        $this->supplierBillItemInfoRepository = $supplierBillItemInfoRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function finishedAirlineBills(Request $request)
    {
        return $this->airlineBills($request,'finished');
    }
    public function newAirlineBills(Request $request)
    {
        return $this->airlineBills($request,'new');
    }
    public function passedAirlineBills(Request $request)
    {
        return $this->airlineBills($request,'passed');
    }
    public function invalidAirlineBills(Request $request)
    {
        return $this->airlineBills($request,'invalid');
    }

    public function airlineBills(Request $request,$status,$view='')
    {
        $view = $view ? $view : $status;
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $bills = $this->repository;

            $bills = is_array($status) ? $bills->whereIn('status',$status) : $bills->where('status',$status);

            $bills = $bills
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($bills as $key => $bill)
            {
                $bill->status_button = $bill->getStatusOneLevelButton($bill->status);
            }
            return $this->response
                ->success()
                ->count($bills->total())
                ->data($bills->toArray()['data'])
                ->output();
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $suppliers = $this->supplierRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('airline_bill.title'))
            ->data(compact('airports','suppliers'))
            ->view('airline_bill.'.$view)
            ->output();

    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $bills = $this->repository;

            $bills = $bills
                ->orderBy('date','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($bills as $key => $bill)
            {
                $bill->status_button = $bill->getStatusOneLevelButton($bill->status);
            }
            return $this->response
                ->success()
                ->count($bills->total())
                ->data($bills->toArray()['data'])
                ->output();
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $suppliers = $this->supplierRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('airline_bill.title'))
            ->data(compact('airports','suppliers'))
            ->view('airline_bill.index')
            ->output();
    }

    public function create(Request $request)
    {
        $data =  $request->all();
        $supplier_bill_id = $data['supplier_bill_id'];
        $supplier_bill = $this->supplierBillRepository->find($supplier_bill_id);
        $airline =  $this->airlineRepository->find(Auth::user()->airline_id);

        $supplier_bill_items = $this->supplierBillItemRepository->where('supplier_bill_id',$supplier_bill_id)->orderBy('flight_date','asc')->get();

        $total = 0;
        foreach ($supplier_bill_items as $key => $supplier_bill_item)
        {
            $supplier_bill_item->infos = $supplier_bill_item->infos->toArray();
            $supplier_bill_item->airline_bill_item = $this->repository->getAirlineBillFromSupplierBill($supplier_bill_item->infos);
            $total += $supplier_bill_item->total;
        }
        $final_total = $total;
        $contract = $airline->contracts->where('airport_id',$supplier_bill->airport_id)->first();
        $final_total = $contract ? $final_total * (1+$contract->increase_price) : $final_total;
        $final_total = floor($final_total*10000)/10000; //round($final_total,4)

        return $this->response->title(trans('app.new') . ' ' . trans('supplier_bill.name'))
            ->view('airline_bill.create')
            ->data(compact('supplier_bill_items','supplier_bill_id','total','final_total'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $attributes['airline_id'] = Auth::user()->airline_id;
            $attributes['airline_name'] = $this->airlineRepository->where('id',$attributes['airline_id'])->value('name');

            $supplier_bill_id = $attributes['supplier_bill_id'];
            $supplier_bill = $this->supplierBillRepository->find($supplier_bill_id);

            if(!in_array($supplier_bill->status,['passed','rebill']))
            {
                throw new OutputServerMessageException(trans('supplier_bill.message.forbid_airline_bill'));
            }

            $attributes['airport_id'] = $supplier_bill->airport_id;
            $attributes['airport_name'] = $supplier_bill->airport_name;
            $attributes['supplier_id'] = $supplier_bill->supplier_id;
            $attributes['supplier_name'] = $supplier_bill->supplier_name;

            $attributes['sn'] = build_order_sn('a');
            $date_arr = explode('~',$attributes['date_of_supply']);
            $attributes['supply_start_date'] = trim($date_arr[0]);
            $attributes['supply_end_date'] = trim($date_arr[1]);
            $airline_bill = $this->repository->create($attributes);

            $this->repository->operation([
                'id' => $airline_bill->id,
                'status' => 'new'
            ]);
            $this->supplierBillRepository->operation([
                'id' => $supplier_bill_id,
                'status' => 'bill'
            ]);
            foreach ($attributes['supplier_bill_item_ids'] as $key => $supplier_bill_item_id)
            {
                $this->airlineBillItemRepository->create([
                    'airline_id' => $attributes['airport_id'],
                    'airline_name' => $attributes['airport_name'],
                    'airport_id' => $supplier_bill['airport_id'],
                    'airport_name' => $supplier_bill['airport_name'],
                    'supplier_id' => $supplier_bill['supplier_id'],
                    'supplier_name' => $supplier_bill['supplier_name'],
                    'supplier_bill_item_id' => $supplier_bill_item_id,
                    'airline_bill_id' => $airline_bill->id,
                    'date' => $attributes['date'][$key],
                    'usg' => $attributes['usg'][$key],
                    'price' => $attributes['price'][$key],
                    'sum' => $attributes['sum'][$key],
                    'tax' => $attributes['tax'][$key],
                    'incl_tax' => $attributes['incl_tax'][$key],
                ]);
            }

            return $this->response->message(trans('messages.success.created', ['Module' => trans('airline_bill.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('new_airline_bill'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }

    }
    public function show(Request $request,AirlineBill $airline_bill)
    {
        if ($airline_bill->exists) {
            $view = 'airline_bill.show';
        } else {
            $view = 'airline_bill.new';
        }

        $airline_bill_items = $this->airlineBillItemRepository
            ->where('airline_bill_id',$airline_bill->id)
            ->orderBy('date','asc')
            ->get();

        return $this->response->title(trans('app.view') . ' ' . trans('airline_bill.name'))
            ->data(compact('airline_bill','airline_bill_items'))
            ->view($view)
            ->output();
    }
    public function update(Request $request, AirlineBill $airline_bill)
    {
        try {
            $attributes = $request->all();

            $date_arr = explode('~',$attributes['date_of_supply']);
            $attributes['supply_start_date'] = trim($date_arr[0]);
            $attributes['supply_end_date'] = trim($date_arr[1]);

            $airline_bill->update($attributes);

            foreach ($attributes['airline_bill_item_ids'] as $key => $airline_bill_item_id)
            {
                $this->airlineBillItemRepository->update([
                    'date' => $attributes['date'][$key],
                    'usg' => $attributes['usg'][$key],
                    'price' => $attributes['price'][$key],
                    'sum' => $attributes['sum'][$key],
                    'tax' => $attributes['tax'][$key],
                    'incl_tax' => $attributes['incl_tax'][$key],
                ],$airline_bill_item_id);
            }
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airline_bill.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('new_airline_bill'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }
    }


    public function invalid(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $airline_bill = $this->repository->find($data['id']);

            bii_operation_verify($airline_bill->status,['new']);

            $this->repository->operation([
                'id' => $id,
                'status' => 'invalid'
            ]);
            $supplier_bill_status = 'rebill';

            if(isset($data['type']) && $data['type'] == 'all')
            {
                $supplier_bill_status = 'invalid';
            }

            $this->supplierBillRepository->operation([
                'id' => $airline_bill->supplier_bill_id,
                'status' => $supplier_bill_status
            ]);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airline_bill'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airline_bill'))
                ->redirect();
        }
    }
    public function pay(Request $request,AirlineBill $airline_bill)
    {

        return $this->response->title(trans('app.pay') . ' ' . trans('airline_bill.name'))
            ->view('airline_bill.pay')
            ->data(compact('airline_bill'))
            ->output();
    }
    public function paySubmit(Request $request,AirlineBill $airline_bill)
    {
        try {
            $attributes = $request->all();
            $attributes['pay_status'] = 'paid';
            $airline_bill->update($attributes);
            $this->repository->operation([
                'id' => $airline_bill->id,
                'status' => 'finished'
            ]);
            $this->supplierBillRepository->operation([
                'id' => $airline_bill->supplier_bill_id,
                'status' => 'finished'
            ]);
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airline_bill.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('finished_airline_bill'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_bill/pay/'.$airline_bill->id))
                ->redirect();
        }
    }
}