<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\AirlineBill;
use App\Models\operation;
use App\Models\SupplierBill;
use App\Repositories\Eloquent\OperationRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class OperationResourceController extends BaseController
{
    public function __construct(OperationRepository $operationRepository,
                                AirportRepository $airportRepository,
                                WorldCityRepository $worldCityRepository)
    {
        parent::__construct();
        $this->repository = $operationRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';

        if ($this->response->typeIs('json')) {
            $operations = $this->repository;
            /*if(!empty($search_name))
            {
                $operations = $operations->where(function ($query) use ($search_name){
                    return $query->where('name','like','%'.$search_name.'%');
                });
            }
            */
            $operations =$operations->orderBy('id','desc')
                ->paginate($limit);

            foreach ($operations as $key => $operation)
            {
                $operation->operationable;
                switch ($operation->operationable)
                {
                    case $operation->operationable instanceof SupplierBill:
                        $operation->type_name = trans('supplier_bill.name');
                    case $operation->operationable instanceof AirlineBill:
                        $operation->type_name = trans('airline_bill.name');
                }
            }

            return $this->response
                ->success()
                ->count($operations->total())
                ->data($operations->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('operation.title'))
            ->view('operation.index')
            ->output();
    }
    public function create(Request $request)
    {
        $operation = $this->repository->newInstance([]);

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        return $this->response->title(trans('operation.title'))
            ->data(compact('operation','airports'))
            ->view('operation.create')
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

            $operation = $this->repository->create($attributes);

            $operation->canCooperativeAirports()->sync($airports);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('operation.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('operation'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('operation'))
                ->redirect();
        }
    }
    public function show(Request $request,operation $operation)
    {
        if ($operation->exists) {
            $view = 'operation.show';
        } else {
            $view = 'operation.create';
        }

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        $canCooperativeAirports = $this->repository->get_cooperative_operation_airports($operation->id);
        $can_cooperative_airport_ids = array_column($canCooperativeAirports->toArray(),'airport_id');

        return $this->response->title(trans('app.view') . ' ' . trans('operation.name'))
            ->data(compact('operation','airports','can_cooperative_airport_ids'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,operation $operation)
    {
        try {
            $attributes = $request->all();

            $attributes['country'] = isset($attributes['country_id']) ? $this->worldCityRepository->where('id',$attributes['country_id'])->value('name_en') : $operation->country;
            $attributes['province'] =  isset($attributes['province_id']) ? $this->worldCityRepository->where('id',$attributes['province_id'])->value('name_en') : $operation->province;
            $attributes['city'] = isset($attributes['city_id']) ? $this->worldCityRepository->where('id',$attributes['city_id'])->value('name_en') : $operation->city;

            $operation->update($attributes);

            $airports = $request->get('airports',[]);
            if($airports)
            {
                $operation->canCooperativeAirports()->sync($airports);
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('operation.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('operation'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('operation/' . $operation->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,operation $operation)
    {
        try {
            $this->repository->forceDelete([$operation->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('operation.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('operation'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('operation'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('operation.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('operation'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('operation'))
                ->redirect();
        }
    }
}
