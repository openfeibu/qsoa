<?php

namespace App\Http\Controllers;


use App\Models\Contract;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;


class HomeController extends BaseController
{
    public function __construct(SupplierRepository $supplierRepository,
        AirportRepository $airportRepository,
        AirlineRepository $airlineRepository
    )
    {
        parent::__construct();
        $this->supplierRepository = $supplierRepository;
        $this->airportRepository = $airportRepository;
        $this->airlineRepository = $airlineRepository;
    }

    public function selectSupplier(Request $request,$supplier_id)
    {
        $supplier = $this->supplierRepository->find($supplier_id);
        $airport_ids = $supplier->contracts->pluck('airport_id');
        $airports = $this->airportRepository->whereIn('id',$airport_ids)->orderBy('id','desc')->get();

        return $this->response
            ->success()
            ->count($airports->count())
            ->data($airports->toArray())
            ->json();
    }
    public function selectAirport(Request $request,$airport_id)
    {
        $airline_ids = Contract::where('airport_id',$airport_id)->where('contractable_type','App\Models\Airline')->pluck('contractable_id');

        $airlines = $this->airlineRepository->whereIn('id',$airline_ids)->orderBy('id','desc')->get();

        return $this->response
            ->success()
            ->count($airlines->count())
            ->data($airlines->toArray())
            ->json();
    }
}
