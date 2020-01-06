<?php

namespace App\Repositories\Eloquent;

use Auth;
use App\Models\AirlineBill;
use App\Models\AirlineBillRecord;
use App\Repositories\Eloquent\AirlineBillRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirlineBillRepository extends BaseRepository implements AirlineBillRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.airline.airline_bill.search');
    }

    public function model()
    {
        return config('model.airline.airline_bill.model');
    }
    public function operation($data)
    {
        try{
            AirlineBill::where('id',$data['id'])->update([
                'status' => $data['status'],
            ]);
            AirlineBillRecord::create([
                'airline_bill_id' => $data['id'],
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'status' => $data['status'],
                'content' => $data['content'] ?? ''
            ]);
            if($data['status'] == 'invalid')
            {
                $this->refund($data['id']);
            }
            app(OperationRepository::class)->createOperation([
                'operationable_id' => $data['id'],
                'operationable_type' => config('model.airline.airline_bill.model'),
                'content' => trans('airline_bill.status.operation.'.$data['status']),
            ]);
            return true;
        }catch (Exception $e) {
            throw new OutputServerMessageException($e->getMessage());
        }
    }
    public function getAirlineBillFromSupplierBill($infos)
    {
        $item = [
            'usg' => '',
            'usd_usg' => '',
            'sum' => '',
        ];
        foreach ($infos as $key => $info)
        {
            switch ($info['field_mark'])
            {
                case 'USG':
                    $item['usg'] = $info['field_value'];
                    break;
                case 'USD/USG':
                    $item['price'] = $info['field_value'];
                    break;
                case 'SUM':
                    $item['sum'] = $info['field_value'];
                    break;
            }
        }
        return $item;
    }
    public function refund($id)
    {
        $supplier_bill = AirlineBill::where('id',$id)->first();
        if($supplier_bill->pay_status == 'paid')
        {
            AirlineBill::where('id',$id)->update([
                'pay_status' => 'refund',
            ]);
        }
    }
}