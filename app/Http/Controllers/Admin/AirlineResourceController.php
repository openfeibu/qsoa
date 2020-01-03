<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\airline;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class AirlineResourceController extends BaseController
{
    public function __construct(AirlineRepository $airlineRepository,
                                AirportRepository $airportRepository,
                                WorldCityRepository $worldCityRepository)
    {
        parent::__construct();
        $this->repository = $airlineRepository;
        $this->airportRepository = $airportRepository;
        $this->worldCityRepository= $worldCityRepository;
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';

        if ($this->response->typeIs('json')) {
            $airlines = $this->repository;
            if(!empty($search_name))
            {
                $airlines = $airlines->where(function ($query) use ($search_name){
                    return $query->where('name','like','%'.$search_name.'%');
                });
            }
            $airlines =$airlines->orderBy('id','desc')
                ->paginate($limit);

            foreach ($airlines as $key => $airline)
            {
                $airport_ids = $airline->contracts->pluck('airport_id');
                $airline->cooperative_airports = $this->airportRepository->whereIn('id',$airport_ids)->orderBy('id','desc')->get();
            }

            return $this->response
                ->success()
                ->count($airlines->total())
                ->data($airlines->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('airline.title'))
            ->view('airline.index')
            ->output();
    }
    public function create(Request $request)
    {
        $airline = $this->repository->newInstance([]);

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        return $this->response->title(trans('airline.title'))
            ->data(compact('airline','airports'))
            ->view('airline.create')
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

            $airline = $this->repository->create($attributes);

            $airline->canCooperativeAirports()->sync($airports);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('airline.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airline'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline'))
                ->redirect();
        }
    }
    public function show(Request $request,airline $airline)
    {
        if ($airline->exists) {
            $view = 'airline.show';
        } else {
            $view = 'airline.create';
        }

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        $canCooperativeAirports = $this->repository->get_cooperative_airline_airports($airline->id);
        $can_cooperative_airport_ids = array_column($canCooperativeAirports->toArray(),'airport_id');

        return $this->response->title(trans('app.view') . ' ' . trans('airline.name'))
            ->data(compact('airline','airports','can_cooperative_airport_ids'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,airline $airline)
    {
        try {
            $attributes = $request->all();

            $attributes['country'] = isset($attributes['country_id']) ? $this->worldCityRepository->where('id',$attributes['country_id'])->value('name_en') : $airline->country;
            $attributes['province'] =  isset($attributes['province_id']) ? $this->worldCityRepository->where('id',$attributes['province_id'])->value('name_en') : $airline->province;
            $attributes['city'] = isset($attributes['city_id']) ? $this->worldCityRepository->where('id',$attributes['city_id'])->value('name_en') : $airline->city;

            $airline->update($attributes);

            $airports = $request->get('airports',[]);
            if($airports)
            {
                $airline->canCooperativeAirports()->sync($airports);
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airline.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airline'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline/' . $airline->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,airline $airline)
    {
        try {
            $this->repository->forceDelete([$airline->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airline'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airline'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airline'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airline'))
                ->redirect();
        }
    }
}
