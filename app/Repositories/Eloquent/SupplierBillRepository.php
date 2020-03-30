<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\OutputServerMessageException;
use App\Models\Operation;
use Auth;
use App\Models\SupplierBill;
use App\Models\SupplierBillRecord;
use App\Repositories\Eloquent\SupplierBillRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Eloquent\OperationRepository;

class SupplierBillRepository extends BaseRepository implements SupplierBillRepositoryInterface
{
    public function boot()
    {
        $this->fieldSearchable = config('model.supplier.supplier_bill.search');
    }

    public function model()
    {
        return config('model.supplier.supplier_bill.model');
    }

    public function operation($data)
    {
        try{
            SupplierBill::where('id',$data['id'])->update([
                'status' => $data['status'],
            ]);
            SupplierBillRecord::create([
                'supplier_bill_id' => $data['id'],
                'admin_id' => Auth::user()->id,
                'admin_name' => Auth::user()->name,
                'admin_model' => get_admin_model(Auth::user()),
                'status' => $data['status'],
                'content' => $data['content'] ?? ''
            ]);
            if(in_array($data['status'],['invalid','rejected']))
            {
                //$this->refund($data['id']);
            }
            app(OperationRepository::class)->createOperation([
                'operationable_id' => $data['id'],
                'operationable_type' => config('model.supplier.supplier_bill.model'),
                'content' => trans('supplier_bill.status.operation.'.$data['status']),
            ]);
            return true;
        }catch (Exception $e) {
            throw new OutputServerMessageException($e->getMessage());
        }
    }
    public function refund($id)
    {
        $supplier_bill = SupplierBill::where('id',$id)->first();
        if($supplier_bill->pay_status == 'paid')
        {
            app(SupplierBalanceRecordRepository::class)->refund(
                $supplier_bill->supplier_id,
                $supplier_bill->paid_total,
                [
                    'out_trade_no' => $supplier_bill->sn,
                    'trade_type' => 'REFUND_SUPPLIER_BILL',
                    'description' => '供应商账单退款',
                ]
            );

            SupplierBill::where('id',$id)->update([
                'pay_status' => 'refund',
            ]);
        }
    }
}