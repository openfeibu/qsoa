<?php

namespace App\Http\Controllers;

use App\Http\Response\ResourceResponse;
use Illuminate\Routing\Controller as BaseController;
use App\Models\WorldCity;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class WorldCityResourceController extends BaseController
{
    public function __construct(WorldCityRepository $worldCityRepository)
    {
        $this->repository = $worldCityRepository;
    }
   public function getList(Request $request)
   {
       $parent_id = $request->get('parent_id');

       $cities = $this->repository->getList($parent_id);

       return app(ResourceResponse::class)
           ->success()
           ->count($cities->count())
           ->data($cities->toArray())
           ->json();
   }
}
