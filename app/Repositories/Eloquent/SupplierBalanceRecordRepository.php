<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\OutputServerMessageException;
use App\Models\Supplier;
use App\Models\SupplierBalanceRecord;
use App\Repositories\Eloquent\SupplierBalanceRecordRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Models\User;
use Auth;

class SupplierBalanceRecordRepository extends BaseRepository implements SupplierBalanceRecordRepositoryInterface
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return config('model.supplier.supplier_balance_record.model');
    }
    public function average($supplier_id)
    {
        $start_date = date('Y-m-d',strtotime('-7 day'));
        $end_date = date('Y-m-d',strtotime('-1 day'));

        $count = SupplierBalanceRecord::selectRaw("count(distinct date) as count")
            ->whereBetween('date',[$start_date,$end_date])
            ->where('supplier_id',$supplier_id)
            ->where('type','-1')
            ->value('count');

        $total =  SupplierBalanceRecord::whereBetween('date',[$start_date,$end_date])
            ->where('supplier_id',$supplier_id)
            ->where('type','-1')
            ->sum('price');

        $average = $count ? $total / $count : 0;

        return $average;
    }
    public function pay($supplier_id,$total,$data)
    {
//        return [
//            'return_code' => 'SUCCESS',
//        ];

        $supplier = Supplier::where('id',$supplier_id)->first();

        $new_balance = $supplier->balance - $total;
        if($new_balance < 0)
        {
            throw new OutputServerMessageException('供应商余额不足');
        }
        $update_balance = Supplier::where('id',$supplier_id)->update(['balance' => $new_balance]);
        Supplier::where('id',$supplier_id)->update(['used_balance' => $supplier->used_balance + $total]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => -1,
                'date' => date('Y-m-d'),
                'out_trade_no' => $data['out_trade_no'],
                'trade_type' => $data['trade_type'],
                'description' => $data['description'],
            );
            $this->create($balanceData);
            return [
                'return_code' => 'SUCCESS',
            ];
        }else{
            throw new OutputServerMessageException('支付失败');
        }
    }
    public function refund($supplier_id,$total,$data)
    {
        $supplier = Supplier::where('id',$supplier_id)->first();
        $new_balance = $supplier->balance + $total;
        $update_balance = Supplier::where('id',$supplier_id)->update(['balance' => $new_balance]);
        Supplier::where('id',$supplier_id)->update(['used_balance' => $supplier->used_balance - $total]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => 1,
                'date' => date('Y-m-d'),
                'out_trade_no' => $data['out_trade_no'],
                'trade_type' => $data['trade_type'],
                'description' => $data['description'],
            );
            $this->create($balanceData);
            return [
                'return_code' => 'SUCCESS',
            ];
        }else{
            throw new OutputServerMessageException('退款失败');
        }
    }
    public function topUp($supplier_id,$total)
    {
        $supplier = Supplier::where('id',$supplier_id)->first();

        $new_balance = $supplier->balance + $total;
        $update_balance = Supplier::where('id',$supplier_id)->update(['balance' => $new_balance]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => 1,
                'date' => date('Y-m-d'),
                'out_trade_no' => build_order_sn('tp'),
                'trade_type' => 'TOP_UP',
                'description' => '充值',
            );
            $this->create($balanceData);
            return [
                'return_code' => 'SUCCESS',
            ];
        }else{
            throw new OutputServerMessageException('充值失败');
        }
    }
    public function feeDeduction($supplier_id,$total)
    {
        $supplier = Supplier::where('id',$supplier_id)->first();

        $new_balance = $supplier->balance - $total;
        if($new_balance < 0)
        {
            throw new OutputServerMessageException('供应商余额不足');
        }
        $update_balance = Supplier::where('id',$supplier_id)->update(['balance' => $new_balance]);
        Supplier::where('id',$supplier_id)->update(['used_balance' => $supplier->used_balance + $total]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => -1,
                'date' => date('Y-m-d'),
                'out_trade_no' => build_order_sn('fd'),
                'trade_type' => 'FEE_DEDUCTION',
                'description' => '充值',
            );
            $this->create($balanceData);
            return [
                'return_code' => 'SUCCESS',
            ];
        }else{
            throw new OutputServerMessageException('扣费失败');
        }
    }

    public function dayConsumeDeduction()
    {

        $suppliers = Supplier::where(function ($query) {
            $time = date('Y-m-d 00:00:00');
            return $query->where('last_day_consume','<',$time)->orWhereNull('last_day_consume');
        })->where(function ($query) {
            return $query->where('day_consume','>',0)->whereNotNull('day_consume');
        })->get();

        foreach ($suppliers as $key => $supplier)
        {
            if($supplier->balance < $supplier->day_consume)
            {
                $content = $supplier->name.' 余额不足，日消费扣款失败';
                app(MessageRepository::class)->createMessage([
                    'admin_group' => config('model.supplier_user.supplier_user.model.model'),
                    'content' => $content
                ]);
                continue;
            }

            $new_balance = $supplier->balance - $supplier->day_consume;
            $balanceData = array(
                'admin_id' => 1,
                'admin_name' => 'Super User',
                'admin_model' => config('model.user.admin.model.model'),
                'supplier_id' => $supplier->id,
                'supplier_name' => $supplier->name,
                'balance' => $new_balance,
                'price'	=> $supplier->day_consume,
                'type' => -1,
                'date' => date('Y-m-d'),
                'out_trade_no' => build_order_sn('dcd'),
                'trade_type' => 'DAY_CONSUME_DEDUCTION',
                'description' => '日消费',
            );
            $this->create($balanceData);

            Supplier::where('id',$supplier->id)->update(['last_day_consume' => date('Y-m-d H:i:s'),'balance' => $new_balance,'used_balance' => $supplier->used_balance + $supplier->day_consume]);

        }

    }
}
