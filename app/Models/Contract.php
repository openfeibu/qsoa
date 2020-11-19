<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class Contract extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.contract.contract';

    protected $appends = ['images','remaining_day','remaining_day_span'];

    public function contractable()
    {
        return $this->morphTo();
    }
    public function getImagesAttribute()
    {
        //return $this->attributes ? ContractImage::where('contract_id',$this->attributes['id'])->orderBy('order','asc')->orderBy('id','asc')->pluck('url')->toArray() : [];
        return $this->attributes ? ContractImage::where('contract_id',$this->attributes['id'])->orderBy('order','asc')->orderBy('id','asc')->value('url') : '';
    }

    public function airport()
    {
        return $this->belongsTo('App\Models\Airport');
    }
    public function getRemainingDayAttribute()
    {
        if(!$this->attributes['end_time'])
        {
            return "";
        }
        return diffBetweenTwoDays(date('Y-m-d',strtotime($this->attributes['end_time'])),date('Y-m-d'));
    }
    public function getRemainingDaySpanAttribute()
    {
        if($this->remaining_day < 30)
        {
            return "<span style='color:#FF5722'>".$this->remaining_day."</span>";
        }
        return $this->remaining_day;
    }
}