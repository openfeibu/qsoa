<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;
use App\Traits\Area;

class AirlineBillTemplateField extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity, Area;

    protected $config = 'model.airline.airline_bill_template_field';



}