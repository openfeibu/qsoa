<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\BaseModel;
use App\Traits\Database\Slugger;
use App\Traits\Filer\Filer;
use App\Traits\Hashids\Hashids;
use App\Traits\Trans\Translatable;

class SupplierBill extends BaseModel
{
    use Filer, Hashids, Slugger, Translatable, LogsActivity;

    protected $config = 'model.supplier.supplier_bill';

    protected $appends = ['remaining_day','remaining_day_span'];

    public function airport()
    {
        return $this->belongsTo('App\Models\Airport');
    }

    public function getStatusOneLevelDesc($status)
    {
        return trans('supplier_bill.status.one-level.'.$status);
    }
    public function getStatusTwoLevelDesc($status)
    {
        return trans('supplier_bill.status.two-level.'.$status);
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
    public function getPayStatusButton($pay_status)
    {
        $html = '<button class="layui-btn %s layui-btn-xs">%s</button>';
        return sprintf($html,config($this->config.'.pay_status_button.'.$pay_status), trans('supplier_bill.pay_status.'.$pay_status));
    }
    public function infos()
    {
        return $this->hasMany(config('model.supplier.supplier_bill_item_info.model'));
    }
    public function getRemainingDayAttribute()
    {
        if(!$this->attributes['pay_date'] || $this->attributes['paid_date'])
        {
            return "";
        }
        return diffBetweenTwoDays(date('Y-m-d',strtotime($this->attributes['pay_date'])),date('Y-m-d'));
    }
    public function getRemainingDaySpanAttribute()
    {
        if($this->remaining_day < 0)
        {
            return "<span style='color:#FF5722'>".$this->remaining_day."</span>";
        }
        return $this->remaining_day;
    }
}