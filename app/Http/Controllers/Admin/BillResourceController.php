<?php
namespace App\Http\Controllers\Admin;

use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Admin\ResourceController as BaseController;
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
class BillResourceController extends BaseController
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

    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $bills = $this->repository;

            $bills = $bills
                ->whereIn('status',['finished'])
                ->orderBy('paid_date','desc')
                ->orderBy('id','desc')
                ->paginate($limit);

            foreach ($bills as $key => $bill)
            {
                $bill->supplier_bill = $bill->supplier_bill;
            }

            return $this->response
                ->success()
                ->count($bills->total())
                ->data($bills->toArray()['data'])
                ->output();
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();

        return $this->response->title(trans('bill.title'))
            ->data(compact('airports'))
            ->view('bill.index')
            ->output();
    }

    public function show(Request $request,AirlineBill $bill)
    {
        if ($bill->exists) {
            $view = 'bill.show';
        } else {
            $view = 'bill.new';
        }

        $airline_bill_items = $this->airlineBillItemRepository
            ->where('airline_bill_id',$bill->id)
            ->orderBy('date','asc')
            ->get();

        return $this->response->title(trans('app.view') . ' ' . trans('bill.name'))
            ->data(compact('bill','airline_bill_items'))
            ->view($view)
            ->output();
    }
}