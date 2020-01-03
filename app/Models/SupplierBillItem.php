<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class SupplierBillItem extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.supplier.supplier_bill_item';

    public function airport()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function infos()
    {
        return $this->hasMany(config('model.supplier.supplier_bill_item_info.model'));
    }
}