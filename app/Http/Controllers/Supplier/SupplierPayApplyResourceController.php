<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
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

        if(!$supplier_pay_apply)
        {
            $supplier_pay_apply = $this->repository->newInstance([]);
            $view = 'supplier_pay_apply.create';
        }
        return $this->response->title(trans('supplier_pay_apply.title'))
            ->data(compact('supplier_bill','supplier_pay_apply'))
            ->view($view)
            ->output();
    }
    public function supplierPayApplyStore(Request $request, SupplierBill $supplier_bill)
    {
        try {
            $attributes = $request->all();

            $attributes['supplier_bill_id'] = $supplier_bill->id;

            $supplier_pay_apply = $this->repository->where('supplier_bill_id',$supplier_bill->id)->first();

            if(!$supplier_pay_apply)
            {
                $supplier_pay_apply = $this->repository->create($attributes);
            }
            else{
                $supplier_pay_apply->update($attributes);
            }
            $supplier_bill->update([
                'pay_status' => 'request_pay'
            ]);


            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier_pay_apply.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_bill'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_bill/supplier_pay_apply/' . $supplier_bill->id))
                ->redirect();
        }
    }

    public function create(Request $request)
    {
        $view = 'supplier_pay_apply.show';
        $supplier_bill_id = $request->supplier_bill_id;
        $supplier_bill = $this->supplierBillRepository->find($supplier_bill_id);
        $supplier_pay_apply = $this->repository->where('supplier_bill_id',$supplier_bill_id)->first();
        if(!$supplier_pay_apply)
        {
            $supplier_pay_apply = $this->repository->newInstance([]);
            $view = 'supplier_pay_apply.create';
        }
        return $this->response->title(trans('supplier_pay_apply.title'))
            ->data(compact('supplier_bill','supplier_pay_apply'))
            ->view($view)
            ->output();
    }

    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $supplier_pay_apply = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier_pay_apply.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();
        }
    }
    public function show(Request $request,SupplierPayApply $supplier_pay_apply)
    {
        if ($supplier_pay_apply->exists) {
            $view = 'supplier_pay_apply.show';
        } else {
            $view = 'supplier_pay_apply.create';
        }

        $airports = $this->airportRepository->orderBy('id','desc')->get();

        $canCooperativeAirports = $this->repository->get_cooperative_supplier_pay_apply_airports($supplier_pay_apply->id);
        $can_cooperative_airport_ids = array_column($canCooperativeAirports->toArray(),'airport_id');

        return $this->response->title(trans('app.view') . ' ' . trans('supplier_pay_apply.name'))
            ->data(compact('supplier_pay_apply','airports','can_cooperative_airport_ids'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,SupplierPayApply $supplier_pay_apply)
    {
        try {
            $attributes = $request->all();

            $supplier_pay_apply->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier_pay_apply.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_pay_apply/' . $supplier_pay_apply->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,SupplierPayApply $supplier_pay_apply)
    {
        try {
            $this->repository->forceDelete($supplier_pay_apply->id);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_pay_apply.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];

            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_pay_apply.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_pay_apply'))
                ->redirect();
        }
    }
}
