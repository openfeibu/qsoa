<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Finance\ResourceController as BaseController;
use App\Models\SupplierBill;
use App\Models\SupplierPayApply;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierPayApplyRepository;
use Illuminate\Http\Request;

class SupplierPayApplyResourceController extends BaseController
{
    public function __construct(
        SupplierPayApplyRepository $supplierPayApplyRepository,
        SupplierBillRepository $supplierBillRepository
        )
    {
        parent::__construct();
        $this->repository = $supplierPayApplyRepository;
        $this->supplierBillRepository = $supplierBillRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }

    public function supplierPayApply(Request $request, SupplierBill $supplier_bill)
    {
        $view = 'supplier_pay_apply.show';

        $supplier_pay_apply = $this->repository->where('supplier_bill_id',$supplier_bill->id)->first();

        return $this->response->title(trans('supplier_pay_apply.title'))
            ->data(compact('supplier_bill','supplier_pay_apply'))
            ->view($view)
            ->output();
    }
    public function reject(Request $request)
    {
        try {
            $data = $request->all();

            $supplier_bill = $this->supplierBillRepository->find($data['supplier_bill_id']);
            $supplier_pay_apply = $this->repository->where('supplier_bill_id',$supplier_bill->id)->first();

            bii_operation_verify($supplier_bill->pay_status,['request_pay']);

            $this->supplierBillRepository->update([
                'pay_status' => 'rejected'
            ],$supplier_bill->id);
            $this->repository->update([
                'remark' => $data['remark']
            ],$supplier_pay_apply->id);

            return $this->response->message(trans('messages.operation.success'))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_bill'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_bill'))
                ->redirect();
        }
    }
}
