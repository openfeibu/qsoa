<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;
use App\Traits\Area;

class Supplier extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity, Area;

    /**
     * Configuartion for the model.
     *
     * @var array
     */
    protected $config = 'model.supplier.supplier';

    protected $appends = ['area','can_cooperative_airports','balance_day','available_until','balance_day_span'];

    public function canCooperativeAirports()
    {
        return $this->belongsToMany('App\Models\Airport', 'can_cooperative_supplier_airport','supplier_id','airport_id')->orderBy('airport_id','desc');
    }

    public function getCanCooperativeAirportsAttribute()
    {
        $airports = $this->canCooperativeAirports()->get();
        return $airports;
    }
    public function getBalanceDayAttribute()
    {
        $balance_day = $this->day_consume && $this->day_consume >0 ? floor($this->balance / $this->day_consume) : 0;
        return $balance_day;
    }
    public function contracts()
    {
        return $this->morphMany('App\Models\Contract', 'contractable');
    }
    public function getAvailableUntilAttribute()
    {
        $available_until = date('Y-m-d',strtotime('+'.$this->balance_day.'day'));
        return $available_until;
    }

    public function getBalanceDaySpanAttribute()
    {
        if($this->balance_day < 10)
        {
            return "<span style='color:#FF5722'>".$this->balance_day."</span>";
        }
        return $this->balance_day;
    }

}