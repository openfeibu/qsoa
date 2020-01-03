<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\OutputServerMessageException;
use App\Models\Airport;
use App\Repositories\Eloquent\AirportBalanceRecordRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Models\User;
use Auth;

class AirportBalanceRecordRepository extends BaseRepository implements AirportBalanceRecordRepositoryInterface
{

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return config('model.airport.airport_balance_record.model');
    }
    public function pay($airport_id,$total,$data)
    {
        $airport = Airport::where('id',$airport_id)->first();
        $new_balance = $airport->balance - $total;
        if($new_balance < 0)
        {
            throw new OutputServerMessageException('机场余额不足');
        }
        $update_balance = Airport::where('id',$airport_id)->update(['balance' => $new_balance]);
        Airport::where('id',$airport_id)->update(['used_balance' => $airport->used_balance + $total]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'airport_id' => $airport->id,
                'airport_name' => $airport->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => -1,
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
    public function refund($airport_id,$total,$data)
    {
        $airport = Airport::where('id',$airport_id)->first();
        $new_balance = $airport->balance + $total;
        $update_balance = Airport::where('id',$airport_id)->update(['balance' => $new_balance]);
        Airport::where('id',$airport_id)->update(['used_balance' => $airport->used_balance - $total]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'airport_id' => $airport->id,
                'airport_name' => $airport->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => 1,
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
    public function topUp($airport_id,$total)
    {
        $airport = Airport::where('id',$airport_id)->first();
        $new_balance = $airport->balance + $total;
        $update_balance = Airport::where('id',$airport_id)->update(['balance' => $new_balance]);

        if($update_balance){
            $balanceData = array(
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'airport_id' => $airport->id,
                'airport_name' => $airport->name,
                'balance' => $new_balance,
                'price'	=> $total,
                'type' => 1,
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
}
