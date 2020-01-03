<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SupplierBillTemplateFieldRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierBillTemplateFieldRepository extends BaseRepository implements SupplierBillTemplateFieldRepositoryInterface
{
    public function model()
    {
        return config('model.supplier.supplier_bill_template_field.model');
    }
    public function fields($supplier_id)
    {
        return $this->where('supplier_id',$supplier_id)
            ->orderBy('order','asc')
            ->orderBy('id','asc')
            ->get();
    }
}