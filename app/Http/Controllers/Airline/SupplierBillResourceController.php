<?php
namespace App\Http\Controllers\Airline;

use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Airline\ResourceController as BaseController;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierBillItemRepository;
use App\Repositories\Eloquent\SupplierBillItemInfoRepository;
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
        AirportRepository $airportRepository,
        AirlineRepository $airlineRepository,
        SupplierRepository $supplierRepository
    )
    {
        parent::__construct();
        $this->repository = $supplierBillRepository;
        $this->airportRepository = $airportRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierBillItemRepository = $supplierBillItemRepository;
        $this->supplierBillItemInfoRepository = $supplierBillItemInfoRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function newSupplierBills(Request $request)
    {
        return $this->supplierBills($request,'new');
    }
    public function passedSupplierBills(Request $request)
    {
        return $this->supplierBills($request,['passed','rebill'],'passed');
    }
    public function invalidSupplierBills(Request $request)
    {
        return $this->supplierBills($request,'invalid');
    }
    public  function billSupplierBills(Request $request)
    {
        return $this->supplierBills($request,'bill');
    }
    public function supplierBills(Request $request,$status,$view='')
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
                ->orderBy('invoice_date','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($bills as $key => $bill)
            {
                $bill->status_button = $bill->getStatusTwoLevelButton($bill->status);
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
            ->view('supplier_bill.'.$view)
            ->output();

    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $bills = $this->repository
                ->where(['airline_id' => Auth::user()->airline_id]);

            $bills = $bills
                ->orderBy('invoice_date','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($bills as $key => $bill)
            {
                $bill->status_button = $bill->getStatusTwoLevelButton($bill->status);
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

    public function pass(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $supplier_bill = $this->repository->find($data['id']);
            if(!in_array($supplier_bill->status,['new','modified']) )
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $this->repository->operation([
                'id' => $id,
                'status' => 'passed'
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
    public function reject(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $supplier_bill = $this->repository->find($data['id']);
            if(!in_array($supplier_bill->status,['new','modified']))
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $this->repository->operation([
                'id' => $id,
                'status' => 'rejected'
            ]);

            return $this->response->message(trans('messages.operation.success'))
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
    public function invalid(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $supplier_bill = $this->repository->find($data['id']);
            if(!in_array($supplier_bill->status,['new','modified','rejected']) )
            {
                throw new OutputServerMessageException(trans('messages.operation.illegal'));
            }
            $this->repository->operation([
                'id' => $id,
                'status' => 'invalid'
            ]);
            return $this->response->message(trans('messages.operation.success'))
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

}