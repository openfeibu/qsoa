<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\ExchangeRateRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ExchangeRateRepository extends BaseRepository implements ExchangeRateRepositoryInterface
{
    public function model()
    {
        return config('model.exchange_rate.exchange_rate.model');
    }

}