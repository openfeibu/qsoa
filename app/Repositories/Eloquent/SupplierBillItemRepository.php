<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\OutputServerMessageException;
use Auth;
use App\Models\SupplierBill;
use App\Models\SupplierBillRecord;
use App\Repositories\Eloquent\SupplierBillItemRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierBillItemRepository extends BaseRepository implements SupplierBillItemRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.supplier.supplier_bill_item.search');
    }

    public function model()
    {
        return config('model.supplier.supplier_bill_item.model');
    }


}