<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;
use App\Traits\Area;

class Airline extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity, Area;

    protected $config = 'model.airline.airline';

    protected $appends = ['area','can_cooperative_airports'];

    public function canCooperativeAirports()
    {
        return $this->belongsToMany('App\Models\Airport', 'can_cooperative_airline_airport','airline_id','airport_id')->orderBy('airport_id','desc');
    }

    public function getCanCooperativeAirportsAttribute()
    {
        $airports = $this->canCooperativeAirports()->get();
        return $airports;
    }

    public function contracts()
    {
        return $this->morphMany('App\Models\Contract', 'contractable');
    }

}