<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AirportRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirportRepository extends BaseRepository implements AirportRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.airport.airport.search');
    }

    public function model()
    {
        return config('model.airport.airport.model');
    }

}