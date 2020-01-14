<?php

namespace App\Http\Controllers\FInance;

use App\Http\Controllers\FInance\ResourceController as BaseController;
use App\Models\Airport;
use App\Repositories\Eloquent\SupplierBalanceRecordRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class AirportResourceController extends BaseController
{
    public function __construct(
        AirportRepository $airportRepository,
        WorldCityRepository $worldCityRepository,
        SupplierBalanceRecordRepository $supplierBalanceRecordRepository)
    {
        parent::__construct();
        $this->repository = $airportRepository;
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
            $airports = $this->repository;
            if(!empty($search_name))
            {
                $airports = $airports->where(function ($query) use ($search_name){
                    return $query->where('name','like','%'.$search_name.'%')->orWhere('code','like','%'.$search_name.'%');
                });
            }
            $airports = $airports->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($airports->total())
                ->data($airports->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('airport.title'))
            ->view('airport.index')
            ->output();
    }
    public function create(Request $request)
    {
        $airport = $this->repository->newInstance([]);

        return $this->response->title(trans('airport.title'))
            ->data(compact('airport'))
            ->view('airport.create')
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['country'] = $attributes['country_id'] ? $this->worldCityRepository->where('id',$attributes['country_id'])->value('name_en') : '';
            $attributes['province'] =  $attributes['province_id'] ? $this->worldCityRepository->where('id',$attributes['province_id'])->value('name_en') : '';
            $attributes['city'] =  $attributes['city_id'] ? $this->worldCityRepository->where('id',$attributes['city_id'])->value('name_en') : '';

            $airport = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('airport.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airport'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airport'))
                ->redirect();
        }
    }
    public function show(Request $request,Airport $airport)
    {
        if ($airport->exists) {
            $view = 'airport.show';
        } else {
            $view = 'airport.create';
        }
        return $this->response->title(trans('app.view') . ' ' . trans('airport.name'))
            ->data(compact('airport'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Airport $airport)
    {
        try {
            $attributes = $request->all();

            $attributes['country'] = isset($attributes['country_id']) ? $this->worldCityRepository->where('id',$attributes['country_id'])->value('name_en') : $airport->country;
            $attributes['province'] =  isset($attributes['province_id']) ? $this->worldCityRepository->where('id',$attributes['province_id'])->value('name_en') : $airport->province;
            $attributes['city'] = isset($attributes['city_id']) ? $this->worldCityRepository->where('id',$attributes['city_id'])->value('name_en') : $airport->city;

            $airport->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airport.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airport'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airport'))
                ->redirect();
        }
    }
    public function destroy(Request $request,Airport $airport)
    {
        try {
            $this->repository->forceDelete([$airport->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airport.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airport'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airport'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airport.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airport'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airport'))
                ->redirect();
        }
    }
    public function topUp(Request $request,Airport $airport)
    {
        try {
            $attributes = $request->all();

            $total = $attributes['total'];
            $this->supplierBalanceRecordRepository->topUp($airport->id,$total);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airport.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airport'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airport'))
                ->redirect();
        }
    }
}
