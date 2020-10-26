<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AirlineBillItemInfoRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirlineBillItemInfoRepository extends BaseRepository implements AirlineBillItemInfoRepositoryInterface
{
    public function model()
    {
        return config('model.airline.airline_bill_item_info.model');
    }

    public function fields($airline_bill_id)
    {
        return $this->model->where('airline_bill_id',$airline_bill_id)->orderBy('id','asc')->distinct('field')->pluck('field')->toArray();
    }
}