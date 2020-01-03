<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SupplierBillItemInfoRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierBillItemInfoRepository extends BaseRepository implements SupplierBillItemInfoRepositoryInterface
{
    public function model()
    {
        return config('model.supplier.supplier_bill_item_info.model');
    }

}