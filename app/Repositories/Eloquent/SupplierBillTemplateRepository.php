<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SupplierBillTemplateRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierBillTemplateRepository extends BaseRepository implements SupplierBillTemplateRepositoryInterface
{
    public function model()
    {
        return config('model.supplier.supplier_bill_template.model');
    }

}