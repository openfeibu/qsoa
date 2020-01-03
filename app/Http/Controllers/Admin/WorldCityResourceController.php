<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\WorldCity;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class WorldCityResourceController extends BaseController
{
    public function __construct(WorldCityRepository $worldCityRepository)
    {
        parent::__construct();
        $this->repository = $worldCityRepository;
    }
   public function getList(Request $request)
   {
       $parent_id = $request->get('parent_id');

       $cities = $this->repository->getList($parent_id);

       return $this->response
           ->success()
           ->count($cities->count())
           ->data($cities->toArray())
           ->json();
   }
}
