<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class ExchangeRate extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $connection = 'mysql_rate';

    protected $config = 'model.exchange_rate.exchange_rate';

}