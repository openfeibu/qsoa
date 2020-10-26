<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\ContinentRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ContinentRepository extends BaseRepository implements ContinentRepositoryInterface
{
    public function model()
    {
        return config('model.continent.continent.model');
    }
    public function continents()
    {
        return $this->orderBy('id','asc')->get();
    }
}