<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\WorldCityRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class WorldCityRepository extends BaseRepository implements WorldCityRepositoryInterface
{
    public function model()
    {
        return config('model.world_city.world_city.model');
    }
    public function getCountries()
    {
        $parent_ids = $this->model->where('parent_id',0)->pluck('id');
        return $this->model->whereIn('parent_id',$parent_ids)->orderBy('name_en','asc')->get();

    }

    public function getList($parent_id=0)
    {
        return $this->where('parent_id',$parent_id)->orderBy('name_en','asc')->get();
    }
}