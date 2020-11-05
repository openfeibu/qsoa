<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SupplierPayApplyRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierPayApplyRepository extends BaseRepository implements SupplierPayApplyRepositoryInterface
{
    public function model()
    {
        return config('model.supplier.supplier_pay_apply.model');
    }

}