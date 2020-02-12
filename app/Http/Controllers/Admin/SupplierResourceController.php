<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
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
    public function create(Request $request)
    {
        $supplier = $this->repository->newInstance([]);

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        return $this->response->title(trans('supplier.title'))
            ->data(compact('supplier','airports'))
            ->view('supplier.create')
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $airports = $request->get('airports');

            $attributes['country'] = $attributes['country_id'] ? $this->worldCityRepository->where('id',$attributes['country_id'])->value('name_en') : '';
            $attributes['province'] =  $attributes['province_id'] ? $this->worldCityRepository->where('id',$attributes['province_id'])->value('name_en') : '';
            $attributes['city'] =  $attributes['city_id'] ? $this->worldCityRepository->where('id',$attributes['city_id'])->value('name_en') : '';

            $supplier = $this->repository->create($attributes);

            $supplier->canCooperativeAirports()->sync($airports);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier.title')]))
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
    public function show(Request $request,Supplier $supplier)
    {
        if ($supplier->exists) {
            $view = 'supplier.show';
        } else {
            $view = 'supplier.create';
        }

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        $canCooperativeAirports = $this->repository->get_cooperative_airline_airports($supplier->id);
        $can_cooperative_airport_ids = array_column($canCooperativeAirports->toArray(),'airport_id');

        return $this->response->title(trans('app.view') . ' ' . trans('supplier.title'))
            ->data(compact('supplier','airports','can_cooperative_airport_ids'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Supplier $supplier)
    {
        try {
            $attributes = $request->all();

            $attributes['country'] = isset($attributes['country_id']) ? $this->worldCityRepository->where('id',$attributes['country_id'])->value('name_en') : $supplier->country;
            $attributes['province'] =  isset($attributes['province_id']) ? $this->worldCityRepository->where('id',$attributes['province_id'])->value('name_en') : $supplier->province;
            $attributes['city'] = isset($attributes['city_id']) ? $this->worldCityRepository->where('id',$attributes['city_id'])->value('name_en') : $supplier->city;

            $supplier->update($attributes);

            $airports = $request->get('airports',[]);
            if($airports)
            {
                $supplier->canCooperativeAirports()->sync($airports);
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier.title')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,Supplier $supplier)
    {
        try {
            $this->repository->forceDelete([$supplier->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier.title')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier.title')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier'))
                ->redirect();
        }
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
