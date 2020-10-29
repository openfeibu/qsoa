<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Models\Currency;
use App\Models\Jinqi;
use App\Repositories\Eloquent\JinqiRepository;
use App\Traits\JinqiResource;
use Illuminate\Http\Request;

class JinqiResourceController extends BaseController
{
    use JinqiResource;

    public function __construct(JinqiRepository $jinqiRepository)
    {
        parent::__construct();
        $this->repository = $jinqiRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }


}
