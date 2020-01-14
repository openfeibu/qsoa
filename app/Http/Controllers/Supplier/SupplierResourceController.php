<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Models\Supplier;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\SupplierRepository;
use App\Repositories\Eloquent\WorldCityRepository;
use App\Repositories\Eloquent\SupplierBalanceRecordRepository;
use Illuminate\Http\Request;

class SupplierResourceController extends BaseController
{
    public function __construct
    (
        SupplierRepository $supplierRepository,
        AirportRepository $airportRepository,
        WorldCityRepository $worldCityRepository,
        SupplierBalanceRecordRepository $supplierBalanceRecordRepository
    )
    {
        parent::__construct();
        $this->repository = $supplierRepository;
        $this->airportRepository = $airportRepository;
        $this->worldCityRepository = $worldCityRepository;
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
            $suppliers = $this->repository;
            if(!empty($search_name))
            {
                $suppliers = $suppliers->where(function ($query) use ($search_name){
                    return $query->where('name','like','%'.$search_name.'%');
                });
            }
            $suppliers = $suppliers->orderBy('id','desc')
                ->paginate($limit);
            foreach ($suppliers as $key => $supplier)
            {
                $airport_ids = $supplier->contracts->pluck('airport_id');
                $supplier->cooperative_airports = $this->airportRepository->whereIn('id',$airport_ids)->orderBy('id','desc')->get();
            }
            return $this->response
                ->success()
                ->count($suppliers->total())
                ->data($suppliers->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('supplier.title'))
            ->view('supplier.index')
            ->output();
    }
    public function topUp(Request $request,Supplier $supplier)
    {
        try {
            $attributes = $request->all();

            $total = $attributes['total'];
            $this->supplierBalanceRecordRepository->topUp($supplier->id,$total);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier'))
                ->redirect();
        }
    }
}
