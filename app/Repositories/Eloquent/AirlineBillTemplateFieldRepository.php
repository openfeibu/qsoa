<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AirlineBillTemplateFieldRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirlineBillTemplateFieldRepository extends BaseRepository implements AirlineBillTemplateFieldRepositoryInterface
{
    public function model()
    {
        return config('model.airline.airline_bill_template_field.model');
    }
    public function fields($airline_id)
    {
        return $this->where('airline_id',$airline_id)
            ->orderBy('order','asc')
            ->orderBy('id','asc')
            ->get();
    }
}