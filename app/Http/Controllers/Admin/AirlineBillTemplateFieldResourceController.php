<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\Airline;
use App\Models\AirlineBillTemplateField;
use App\Repositories\Eloquent\AirlineBillTemplateFieldRepository;
use App\Repositories\Eloquent\AirlineRepository;
use Illuminate\Http\Request;

class AirlineBillTemplateFieldResourceController extends BaseController
{
    public function __construct(AirlineRepository $airlineRepository,
                                AirlineBillTemplateFieldRepository $airlineBillTemplateFieldRepository)
    {
        parent::__construct();
        $this->repository = $airlineBillTemplateFieldRepository;
        $this->airlineRepository = $airlineRepository;
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);

        if ($this->response->typeIs('json')) {
            $fields = $this->repository
                ->orderBy('airline_bill_template_fields.order','asc')
                ->orderBy('airline_bill_template_fields.id','asc')
                ->get(['airline_bill_template_fields.*']);

            return $this->response
                ->success()
                ->count($fields->count())
                ->data($fields->toArray())
                ->output();
        }
        return $this->response->title(trans('airline_bill_template.title'))
            ->data(compact('airlines'))
            ->view('airline_bill_template_field.index')
            ->output();
    }

    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $field = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('airline_bill_template_field.title')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();
        }
    }
    public function show(Request $request,AirlineBillTemplateField $airline_bill_template_field)
    {
        if ($airline_bill_template_field->exists) {
            $view = 'airline_bill_template_field.show';
        } else {
            $view = 'airline_bill_template_field.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('airline_bill_template.title'))
            ->data(compact('airline_bill_template_field'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,AirlineBillTemplateField $airline_bill_template_field)
    {
        try {
            $attributes = $request->all();

            $airline_bill_template_field->update($attributes);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airline_bill_template_field.title')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_bill_template_field/'))
                ->redirect();
        }
    }
    public function destroy(Request $request,AirlineBillTemplateField $airline_bill_template_field)
    {
        try {
            $this->repository->forceDelete([$airline_bill_template_field->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline_bill_template_field.title')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline_bill_template_field.title')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airline_bill_template_field'))
                ->redirect();
        }
    }
}
