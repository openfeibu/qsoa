<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Supplier;
use App\Models\SupplierBillTemplateField;
use App\Repositories\Eloquent\SupplierBillTemplateFieldRepository;
use App\Repositories\Eloquent\SupplierBillTemplateRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Http\Request;

class SupplierBillTemplateFieldResourceController extends BaseController
{
    public function __construct(SupplierRepository $supplierRepository,
                                SupplierBillTemplateRepository $supplierBillTemplateRepository,
                                SupplierBillTemplateFieldRepository $supplierBillTemplateFieldRepository)
    {
        parent::__construct();
        $this->repository = $supplierBillTemplateFieldRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierBillTemplateRepository = $supplierBillTemplateRepository;
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $supplier_id = isset($search['supplier_id']) ? $search['supplier_id'] : '';

        if ($this->response->typeIs('json')) {
            $fields = $this->repository
                ->join('suppliers','suppliers.id','=','supplier_bill_template_fields.supplier_id');
            if(!empty($supplier_id))
            {
                $fields = $fields->where(function ($query) use ($supplier_id){
                    return $query->where('supplier_bill_template_fields.supplier_id','=',$supplier_id);
                });
            }
            $fields = $fields
                ->orderBy('supplier_bill_template_fields.supplier_id','desc')
                ->orderBy('supplier_bill_template_fields.order','asc')
                ->orderBy('supplier_bill_template_fields.id','asc')
                ->get(['supplier_bill_template_fields.*','suppliers.name']);

            return $this->response
                ->success()
                ->count($fields->count())
                ->data($fields->toArray())
                ->output();
        }
        $suppliers = $this->supplierRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('supplier_bill_template.title'))
            ->data(compact('suppliers'))
            ->view('supplier_bill_template_field.index')
            ->output();
    }

    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $field = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier_bill_template_field.title')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();
        }
    }
    public function show(Request $request,SupplierBillTemplateField $supplier_bill_template_field)
    {
        if ($supplier_bill_template_field->exists) {
            $view = 'supplier_bill_template_field.show';
        } else {
            $view = 'supplier_bill_template_field.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('supplier_bill_template.title'))
            ->data(compact('supplier_bill_template_field'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,SupplierBillTemplateField $supplier_bill_template_field)
    {
        try {
            $attributes = $request->all();

            $supplier_bill_template_field->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier_bill_template_field.title')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_bill_template_field/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,SupplierBillTemplateField $supplier_bill_template_field)
    {
        try {
            $this->repository->forceDelete([$supplier_bill_template_field->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_bill_template_field.title')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_bill_template_field.title')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_bill_template_field'))
                ->redirect();
        }
    }
}
