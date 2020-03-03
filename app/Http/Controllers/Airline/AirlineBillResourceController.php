<?php
namespace App\Http\Controllers\Airline;

use App\Exceptions\OutputServerMessageException;
use App\Exports\AirlineBillExport;
use App\Http\Controllers\Airline\ResourceController as BaseController;
use App\Imports\AirlineBillImport;
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
use Excel;
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
            $bills = $this->repository
                ->where('airline_id',Auth::user()->airline_id);

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
//                ->where('airline_id',Auth::user()->airline_id);

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
            ->view('airline_bill.index')
            ->output();
    }

    public function create(Request $request)
    {
        $data =  $request->all();
        $supplier_bill_id = $data['supplier_bill_id'];
        $supplier_bill = $this->supplierBillRepository->find($supplier_bill_id);
        $airline =  $this->airlineRepository->find($supplier_bill->airline_id);

        $supplier_bill_items = $this->supplierBillItemRepository->where('supplier_bill_id',$supplier_bill_id)->orderBy('flight_date','asc')->get();

        $contract = $airline->contracts->where('airport_id',$supplier_bill->airport_id)->first();

        $price =  $contract ? airline_bill_price($supplier_bill->price,$contract->increase_price) : $supplier_bill->price;
        foreach ($supplier_bill_items as $key => $supplier_bill_item)
        {

            $supplier_bill_item->airline_bill_price = $price;
            $supplier_bill_item->airline_bill_total = bill_round($supplier_bill_item->usg * $price);
            /*
            $supplier_bill_item->infos = $supplier_bill_item->infos->toArray();
            $supplier_bill_item->airline_bill_item = $this->repository->getAirlineBillFromSupplierBill($supplier_bill_item->infos);
            $total += $supplier_bill_item->total;
            */
        }

        $total = bill_round($supplier_bill->usg * $price);

        return $this->response->title(trans('app.new') . ' ' . trans('supplier_bill.name'))
            ->view('airline_bill.create')
            ->data(compact('supplier_bill','supplier_bill_items','supplier_bill_id','total','contract'))
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $supplier_bill_id = $attributes['supplier_bill_id'];
            $supplier_bill = $this->supplierBillRepository->find($supplier_bill_id);

            $airline =  $this->airlineRepository->find($supplier_bill->airline_id);
            $contract = $airline->contracts->where('airport_id',$supplier_bill->airport_id)->first();

            if(!in_array($supplier_bill->status,['passed','rebill']))
            {
                throw new OutputServerMessageException(trans('supplier_bill.message.forbid_airline_bill'));
            }

            $price =  $contract ? airline_bill_price($supplier_bill->price,$contract->increase_price) : $supplier_bill->price;

            $total = bill_round($supplier_bill->usg * $price);

            $airline_bill_data = [
                'sn' => build_order_sn('a'),
                'airline_id' => $supplier_bill->airline_id,
                'airline_name' => $supplier_bill->airline_name,
                'airport_id' => $supplier_bill->airport_id,
                'airport_name' => $supplier_bill->airport_name,
                'supplier_id' => $supplier_bill->supplier_id,
                'supplier_name' => $supplier_bill->supplier_name,
                'mt' => $supplier_bill->mt,
                'usg' => $supplier_bill->usg,
                'price' => $price,
                'total' => $total,
                'tax' => 0,
                'incl_tax' => $total,
            ];

            $airline_bill_data = array_merge($airline_bill_data,$attributes);

            $airline_bill = $this->repository->create($airline_bill_data);

            $this->repository->operation([
                'id' => $airline_bill->id,
                'status' => 'new'
            ]);
            $this->supplierBillRepository->operation([
                'id' => $supplier_bill_id,
                'status' => 'bill'
            ]);

            $supplier_bill_items = $this->supplierBillItemRepository->where('supplier_bill_id',$supplier_bill_id)->orderBy('flight_date','asc')->get();
            foreach ($supplier_bill_items as $key => $supplier_bill_item)
            {

                $supplier_bill_item->airline_bill_price = $price;
                $supplier_bill_item->airline_bill_total = bill_round($supplier_bill_item->usg * $price);
                $this->airlineBillItemRepository->create([
                    'airline_id' => $supplier_bill->airline_id,
                    'airline_name' => $supplier_bill->airline_name,
                    'airport_id' => $supplier_bill->airport_id,
                    'airport_name' => $supplier_bill->airport_name,
                    'supplier_id' => $supplier_bill->supplier_id,
                    'supplier_name' => $supplier_bill->supplier_name,
                    'supplier_bill_id' => $supplier_bill->id,
                    'supplier_bill_item_id' => $supplier_bill_item->id,
                    'airline_bill_id' => $airline_bill->id,
                    'mt' => $supplier_bill_item->mt,
                    'usg' => $supplier_bill_item->usg,
                    'price' => $price,
                    'total' => bill_round($supplier_bill_item->usg * $price),
                ]);
            }


            return $this->response->message(trans('messages.success.created', ['Module' => trans('airline_bill.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('airline_bill'))
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

        $airline =  $this->airlineRepository->find($airline_bill->airline_id);

        $contract = $airline->contracts->where('airport_id',$airline_bill->airport_id)->first();

        $supplier_bill = $this->supplierBillRepository->find($airline_bill->supplier_bill_id);

        $airline_bill_items = $this->repository->airlineBillItems($airline_bill->id);

        return $this->response->title(trans('app.view') . ' ' . trans('airline_bill.name'))
            ->data(compact('supplier_bill','airline_bill','airline_bill_items','contract'))
            ->view($view)
            ->output();
    }
    public function update(Request $request, AirlineBill $airline_bill)
    {
        try {
            $attributes = $request->all();


            $airline_bill->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airline_bill.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airline_bill'))
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

    public function destroy(Request $request, AirlineBill $airline_bill)
    {
        try {
            $this->supplierBillRepository->operation([
                'id' => $airline_bill->supplier_bill_id,
                'status' => 'rebill'
            ]);

            $airline_bill->forceDelete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline_bill.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('airline_bill'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
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
                ->url(guard_url('airline_bill'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_bill/pay/'.$airline_bill->id))
                ->redirect();
        }
    }

    public function downloadWord(Request $request,AirlineBill $airline_bill)
    {
        return $this->repository->downloadWord($airline_bill);
    }
    public function downloadExcel(Request $request,AirlineBill $airline_bill)
    {
        $name = $airline_bill->agreement_no.'('.date('YmdHis').').xlsx';
        return Excel::download(new AirlineBillExport($airline_bill), $name);
    }

    public function import(Request $request)
    {
        $supplier_bill_id = $request->get('supplier_bill_id');
        return $this->response->title(trans('airline_bill.name'))
            ->data(compact('supplier_bill_id'))
            ->view('airline_bill.import')
            ->output();
    }
    public function submitImport(Request $request)
    {
        $supplier_bill_id = $request->get('supplier_bill_id');
        $supplier_bill = $this->supplierBillRepository->find($supplier_bill_id);
        set_time_limit(0);
        $file = $request->file;
        isVaildExcel($file);
        $res = (new AirlineBillImport)->toArray($file)[0];
        $res = array_filter($res);
        $all_sheet_count = count($res);

        $supply_start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($res[2][8]))->format('Y-m-d');
        $supply_end_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($res[2][10]))->format('Y-m-d');
        /*
        $airline_name = rtrim(ltrim($res[2][2],' '),' ');
        $airport_name = rtrim(ltrim($res[2][5],' '),' ');
        $airport_name_arr = explode('/',$airport_name);
        if(count($airport_name_arr) > 1)
        {
            $airport_name = rtrim(ltrim($airport_name_arr[0],' '),' ');
        }

        $airline = Airline::where('name',$airline_name)->first();
        if(!$airline)
        {
            throw new OutputServerMessageException('找不到相关的航空公司，请核对航空公司名称');
        }
        $airport = Airport::where('name',$airport_name)->first();
        if(!$airport)
        {
            throw new OutputServerMessageException('找不到相关的机场信息，请核对机场名称');
        }
        $contract_supplier = $airport->contractSupplier;
        if(!$contract_supplier)
        {
            throw new OutputServerMessageException('请设置机场合作的供油公司');
        }
        $supplier = $contract_supplier->contractable;
        if(!$supplier)
        {
            throw new OutputServerMessageException('供油公司不存在');
        }

        */
        $keys = ['flight_date','flight_number','board_number','order_number','num_of_orders','mt','usg','unit','price','total'];
        $total = $mt = $usg = $price = 0;
        $items = [];
        for ($i=6;$i<$all_sheet_count;$i++)
        {
            if($res[$i][0] && strtolower($res[$i][0]) != 'total')
            {
                $flight_date = $res[$i][0];
                $flight_date_arr = explode('.',$flight_date);
                if(count($flight_date_arr) >1)
                {
                    $flight_date = substr(date('Y'),0,2).$flight_date_arr[2].'-'.$flight_date_arr[1].'-'.$flight_date_arr[0];
                }
                else{
                    $flight_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($flight_date)->format('Y-m-d');
                }
                $res[$i][0] = $flight_date;

                $item = [];
                for($j=0;$j<count($keys);$j++)
                {
                    $item[$keys[$j]] = $res[$i][$j];
                }
                $item['mt'] = (float)$item['mt'];
                $mt_usg = (float)substr($item['usg'],strpos($item['usg'],'*')+1);
                $item['usg'] = $item['mt'] * $mt_usg;

                $item['price'] = bill_round($item['price']);
                $item['total'] =  bill_round($item['usg'] * $item['price']);

                $items[] = $item;
                $total += $item['total'];
                $mt += $item['mt'];
                $usg += $item['usg'];
                $price = $item['price'];
            }

        }

        $airline_bill = $this->repository->create([
            'sn' => build_order_sn('a'),
            'supplier_bill_id' => $supplier_bill->id,
            'supplier_id' => $supplier_bill->supplier_id,
            'supplier_name' => $supplier_bill->supplier_name,
            'airport_id' => $supplier_bill->airport_id,
            'airport_name' => $supplier_bill->airport_name,
            'airline_id' => $supplier_bill->airline_id,
            'airline_name' => $supplier_bill->airline_name,
            'mt' => $mt,
            'usg' => $usg,
            'price' => $price,
            'total' => bill_round($usg * $price),
            'tax' => 0,
            'incl_tax' => bill_round($usg * $price),
        ]);
        $this->repository->operation([
            'id' => $airline_bill->id,
            'status' => 'new'
        ]);

        foreach ($items as $key => $item)
        {
            $items[$key]['airline_bill_id'] = $airline_bill->id;
            $items[$key]['supplier_bill_id'] = $supplier_bill->id;
            $items[$key]['supplier_id'] = $supplier_bill->supplier_id;
            $items[$key]['supplier_name'] = $supplier_bill->supplier_name;
            $items[$key]['airport_id'] = $supplier_bill->airport_id;
            $items[$key]['airport_name'] = $supplier_bill->airport_name;
            $items[$key]['airline_id'] = $supplier_bill->airline_id;
            $items[$key]['airline_name'] = $supplier_bill->airline_name;
        }

        AirlineBillItem::insert($items);

        $this->supplierBillRepository->operation([
            'id' => $supplier_bill->id,
            'status' => 'bill'
        ]);

        return $this->response->message(trans('messages.success.created', ['Module' => trans('airline_bill.name')]))
            ->status("success")
            ->code(200)
            ->url(guard_url('airline_bill'))
            ->redirect();

    }
}