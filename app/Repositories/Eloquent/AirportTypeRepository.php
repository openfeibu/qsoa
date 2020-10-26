<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AirportTypeRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirportTypeRepository extends BaseRepository implements AirportTypeRepositoryInterface
{
    public function model()
    {
        return config('model.airport.airport_type.model');
    }
    public function airport_types()
    {
        return $this->orderBy('id','asc')->get();
    }
}