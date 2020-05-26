<?php

namespace App\Repositories\Eloquent;

use App\Models\CanCooperativeAirlineAirport;
use App\Models\Contract;
use App\Repositories\Eloquent\AirlineRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirlineRepository extends BaseRepository implements AirlineRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.airline.airline.search');
    }

    public function model()
    {
        return config('model.airline.airline.model');
    }

    public function get_cooperative_airline_airports($airline_id)
    {
        return CanCooperativeAirlineAirport::where('airline_id',$airline_id)->orderBy('airport_id','desc')->get();
    }
    public function deleteAirline($id)
    {
        Contract::where('contractable_type','App\Models\Airline')->where('contractable_id',$id)->delete();
        $this->forceDelete([$id]);
    }

}