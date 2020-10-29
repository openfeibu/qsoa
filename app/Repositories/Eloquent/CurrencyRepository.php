<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\CurrencyRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class CurrencyRepository extends BaseRepository implements CurrencyRepositoryInterface
{
    public function model()
    {
        return config('model.currency.currency.model');
    }

}