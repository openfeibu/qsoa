<?php

namespace App\Repositories\Eloquent;

use App\Models\CanCooperativeSupplierAirport;
use App\Repositories\Eloquent\SupplierRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.supplier.supplier.search');
    }
    public function model()
    {
        return config('model.supplier.supplier.model');
    }

    public function get_cooperative_airline_airports($supplier_id)
    {
        return CanCooperativeSupplierAirport::where('supplier_id',$supplier_id)->orderBy('airport_id','desc')->get();
    }
}