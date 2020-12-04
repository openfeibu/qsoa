<?php
namespace App\Http\Controllers\Airline;

use App\Exceptions\OutputServerMessageException;
use App\Exports\AirlineBillExport;
use App\Http\Controllers\Airline\ResourceController as BaseController;
use App\Imports\AirlineBillImport;
use App\Models\AirlineBillItem;
use App\Models\AirlineBillItemInfo;
use App\Models\AirlineBillTemplateField;
use App\Repositories\Eloquent\AirlineBillItemInfoRepository;
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
        AirlineBillItemInfoRepository $airlineBillItemInfoRepository,
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
        $this->airlineBillItemInfoRepository = $airlineBillItemInfoRepository;
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
    /*弃用*/
    /*
    public function airlineBills(Request $request,$status,$view='')
    {
        $view = $view ? $view : $status;
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        if ($this->response->typeIs('json')) {
            $bills = $this->repository->join('supplier_bills','supplier_bills.id','airline_bills.supplier_bill_id');
                //->where('airline_id',Auth::user()->airline_id);

            if(isset($search['billing_date']) && $search['billing_date'])
            {
                $bills->where(function ($query) use ($search){
                    $billing_date = $search['billing_date'];
                    $billing_date_arr = explode('-',$billing_date);
                    $days = cal_days_in_month(CAL_GREGORIAN, ltrim($billing_date_arr[1],'0'), $billing_date_arr[0]);

                    return $query->whereBetween('supplier_bills.supply_start_date',[$billing_date.'-01',$billing_date.'-'.$days]);
                });
            }

            $bills = is_array($status) ? $bills->whereIn('status',$status) : $bills->where('status',$status);

            $bills = $bills
                ->orderBy('id','desc')
                ->paginate($limit,['airline_bills.*']);
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
        $suppliers = $this->supplierRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('airline_bill.title'))
            ->data(compact('airports','suppliers'))
            ->view('airline_bill.'.$view)
            ->output();

    }
*/
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        if ($this->response->typeIs('json')) {
            $bills = $this->repository
                    ->join('supplier_bills','supplier_bills.id','airline_bills.supplier_bill_id');
//                ->where('airline_id',Auth::user()->airline_id);

            if(isset($search['billing_date']) && $search['billing_date'])
            {
                $bills->where(function ($query) use ($search){
                    $billing_date = $search['billing_date'];
                    $billing_date_arr = explode('-',$billing_date);
                    $days = cal_days_in_month(CAL_GREGORIAN, ltrim($billing_date_arr[1],'0'), $billing_date_arr[0]);

                    return $query->whereBetween('supplier_bills.supply_start_date',[$billing_date.'-01',$billing_date.'-'.$days]);
                });
            }

            $bills = $bills
                ->orderBy('id','desc')
                ->paginate($limit,['airline_bills.*']);
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

        $fields = $this->airlineBillItemInfoRepository->fields($airline_bill->id);

        return $this->response->title(trans('app.view') . ' ' . trans('airline_bill.name'))
            ->data(compact('supplier_bill','airline_bill','airline_bill_items','contract','fields'))
            ->view($view)
            ->output();
    }
    public function update(Request $request, AirlineBill $airline_bill)
    {
        try {
            if(!in_array($airline_bill->status,['new','rejected','modified']) )
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }

            $attributes = $request->all();

            $airline_bill->update($attributes);

            if($airline_bill['status'] == 'rejected')
            {
                $this->repository->operation([
                    'id' => $airline_bill->id,
                    'status' => 'modified',
                ]);
            }

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
    public function checkSubmit(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $airline_bill = $this->repository->find($data['id']);

            bii_operation_verify($airline_bill->status,['new','rejected','modified']);

            $this->repository->operation([
                'id' => $id,
                'status' => 'checking'
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

    public function invalid(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $airline_bill = $this->repository->find($data['id']);

            bii_operation_verify($airline_bill->status,['new','checking','modified','rejected','passed','rebill']);

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
        $name = $airline_bill->sn.'('.date('YmdHis').').xlsx';
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

        //$supply_start_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($res[2][8]))->format('Y-m-d');
        //$supply_end_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($res[2][10]))->format('Y-m-d');
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
        //$keys = ['flight_date','flight_number','board_number','order_number','num_of_orders','mt','usg','unit','price','total'];

        $template_fields = AirlineBillTemplateField::orderBy('order','asc')->orderBy('id','asc')->get();

        $excel_fields = [];
        foreach ($res['4'] as $key => $excel_field)
        {
            $excel_fields[] = $excel_field ? $excel_field : $res['3'][$key];
        }
        $template_fields_key_arr = [];
        $system_template_fields_key_arr = [];
        foreach ($template_fields as $key => $field)
        {
            if($field['system'] == 0)
            {
                $template_fields_key_arr[$key] = [
                    'field' => $field['field'],
                    'field_comment' => $field['field_comment'],
                    //'field_type' => $field['field_type'],
                    'field_mark' => $field['field_mark'],

                ];
                if(in_array($field['field'],$excel_fields))
                {
                    $template_fields_key_arr[$key]['seq'] = array_search($field['field'],$excel_fields);
                }else{
                    $template_fields_key_arr[$key]['seq'] = '';
                }
            }else{
//                $system_template_fields_key_arr[$field['field']] = [
//                    'field' => $field['field'],
//                    'field_comment' => $field['field_comment'],
//                    'field_type' => $field['field_type'],
//                    'field_mark' => $field['field_mark'],
//
//                ];
                if(in_array($field['field'],$excel_fields))
                {
                    $system_template_fields_key_arr[$field['field']] = array_search($field['field'],$excel_fields);
                }else{
                    $system_template_fields_key_arr[$field['field']] = '';
                }
            }

        }

        //var_dump($system_template_fields_key_arr);exit;

        $total = $litre = $mt = $usg = $price = 0;
        $items = [];
        $infos = [];
        for ($i=5;$i<$all_sheet_count;$i++)
        {
            if($res[$i][0] && strtolower($res[$i][0]) != 'total')
            {
                $info = [];
                foreach ($template_fields_key_arr as $key => $field)
                {
                    $info[$key] = $field;
                    if($field['seq'])
                    {
                        $info[$key]['field_value'] = $res[$i][$field['seq']];
                    }
                    unset($info[$key]['seq']);
                }
                $infos[] = $info;

                $item = [];
                foreach ($system_template_fields_key_arr as $key => $seq)
                {
                    $item[$key] =  $res[$i][$seq];
                }

                $flight_date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject(trim($res[$i][0]))->format('Y-m-d');


                $items[] = $item = [
                    'flight_date' => $flight_date,
                    'litre' => $item['L'],
                    'mt' => $item['MT'],
                    'usg' => $item['USG'],
                    'unit' => $item['Unit'],
                    'price' => $item['Price'],
                    'total' => $item['Amount,USD'],
                ];

                //$items[] = $item;
                $litre += $item['litre'];
                $total += $item['total'];
                $mt += $item['mt'];
                $usg += $item['usg'];
                $price = $item['price'];

            }

        }
       // var_dump($infos);exit;
        //var_dump($items);exit;

        $airline_bill = $this->repository->create([
            'sn' => build_order_sn('a'),
            'supplier_bill_id' => $supplier_bill->id,
            'supplier_id' => $supplier_bill->supplier_id,
            'supplier_name' => $supplier_bill->supplier_name,
            'airport_id' => $supplier_bill->airport_id,
            'airport_name' => $supplier_bill->airport_name,
            'airline_id' => $supplier_bill->airline_id,
            'airline_name' => $supplier_bill->airline_name,
            'litre' => $litre,
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
            $item_data = $item;
            $item_data['airline_bill_id'] = $airline_bill->id;
            $item_data['supplier_bill_id'] = $supplier_bill->id;
            $item_data['supplier_id'] = $supplier_bill->supplier_id;
            $item_data['supplier_name'] = $supplier_bill->supplier_name;
            $item_data['airport_id'] = $supplier_bill->airport_id;
            $item_data['airport_name'] = $supplier_bill->airport_name;
            $item_data['airline_id'] = $supplier_bill->airline_id;
            $item_data['airline_name'] = $supplier_bill->airline_name;

            $airline_bill_item = AirlineBillItem::create($item_data);

            $info_data = [];
            foreach ($infos[$key] as $k => $info) {
                $info_data[] = [
                    'airline_bill_id' => $airline_bill->id,
                    'airline_bill_item_id' => $airline_bill_item->id,
                    'field' => $info['field'],
                    'field_comment' => $info['field_comment'],
                    'field_mark' => $info['field_mark'],
                    'field_value' => $info['field_value']
                ];
            }
            AirlineBillItemInfo::insert($info_data);
        }

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