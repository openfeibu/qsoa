<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AirlineBillItemRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirlineBillItemRepository extends BaseRepository implements AirlineBillItemRepositoryInterface
{
    public function model()
    {
        return config('model.airline.airline_bill_item.model');
    }

}