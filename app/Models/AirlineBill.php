<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class AirlineBill extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.airline.airline_bill';

    protected $appends = ['remaining_day'];

    public function getStatusOneLevelDesc($status)
    {
        return trans('airline_bill.status.one-level.'.$status);
    }
    public function getStatusTwoLevelDesc($status)
    {
        return trans('airline_bill.status.two-level.'.$status);
    }
    public function getStatusOneLevelButton($status)
    {
        $html = '<button class="layui-btn %s layui-btn-xs">%s</button>';
        return sprintf($html,config($this->config.'.status_button.'.$status),$this->getStatusOneLevelDesc($status));
    }
    public function getStatusTwoLevelButton($status)
    {
        $html = '<button class="layui-btn %s layui-btn-xs">%s</button>';
        return sprintf($html,config($this->config.'.status_button.'.$status),$this->getStatusTwoLevelDesc($status));
    }
    public function getRemainingDayAttribute()
    {
        if(!$this->attributes['pay_date'])
        {
            return "";
        }
        return diffBetweenTwoDays(date('Y-m-d',strtotime($this->attributes['pay_date'])),date('Y-m-d'));
    }
    public function supplier_bill()
    {
        return $this->belongsTo('App\Models\SupplierBill');
    }
}