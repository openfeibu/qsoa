<?php

namespace App\Models;

use App\Traits\Area;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class AirportBalanceRecord extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity, Area;

    protected $config = 'model.airport.airport_balance_record';


}