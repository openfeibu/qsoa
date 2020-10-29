<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Models\Currency;
use App\Models\ExchangeRate;
use App\Repositories\Eloquent\ExchangeRateRepository;
use App\Traits\ExchangeRateResource;
use Illuminate\Http\Request;

class ExchangeRateResourceController extends BaseController
{
    use ExchangeRateResource;

    public function __construct(ExchangeRateRepository $exchangeRateRepository)
    {
        parent::__construct();
        $this->repository = $exchangeRateRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }

}
