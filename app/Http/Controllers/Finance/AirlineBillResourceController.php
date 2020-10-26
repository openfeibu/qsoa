<?php
namespace App\Http\Controllers\Finance;

use App\Exceptions\OutputServerMessageException;
use App\Exports\AirlineBillExport;
use App\Http\Controllers\Finance\ResourceController as BaseController;
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
    public function pass(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $airline_bill = $this->repository->find($data['id']);
            if(!in_array($airline_bill->status,['checking']) )
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
    public function reject(Request $request)
    {
        try {
            $data = $request->all();
            $id = $data['id'];
            $airline_bill = $this->repository->find($data['id']);
            if(!in_array($airline_bill->status,['checking']))
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


    public function downloadWord(Request $request,AirlineBill $airline_bill)
    {
        return $this->repository->downloadWord($airline_bill);
    }
    public function downloadExcel(Request $request,AirlineBill $airline_bill)
    {
        $name = $airline_bill->sn.'('.date('YmdHis').').xlsx';
        return Excel::download(new AirlineBillExport($airline_bill), $name);
    }


}