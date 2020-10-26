<?php

namespace App\Models;

use App\Traits\Area;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;


class Airport extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity, Area;

    protected $config = 'model.airport.airport';

    protected $appends = ['area','continent_name','airport_type_name'];

    public function contractSupplier()
    {
        return $this->hasOne(config('model.contract.contract.model'))->where('contractable_type',config('model.supplier.supplier.model'));
    }
    public function getContinentNameAttribute()
    {
        return $this->continent->name;
    }
    public function continent()
    {
        return $this->belongsTo('App\Models\Continent')->withDefault([
            'name' => '未知',
        ]);
    }
    public function getAirportTypeNameAttribute()
    {
        return $this->airport_type->name;
    }
    public function airport_type()
    {
        return $this->belongsTo('App\Models\AirportType')->withDefault([
            'name' => '未知',
        ]);
    }
}