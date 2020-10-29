<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Models\quotation;
use App\Repositories\Eloquent\QuotationRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class QuotationResourceController extends BaseController
{
    public function __construct(QuotationRepository $quotationRepository)
    {
        parent::__construct();
        $this->repository = $quotationRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';

        if ($this->response->typeIs('json')) {
            $quotations = $this->repository;
            if(!empty($search_name))
            {
                $quotations = $quotations->where(function ($query) use ($search_name){
                    return $query->where('name','like','%'.$search_name.'%');
                });
            }
            $quotations = $quotations->orderBy('id','desc')
                ->paginate($limit);
            return $this->response
                ->success()
                ->count($quotations->total())
                ->data($quotations->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('quotation.title'))
            ->view('quotation.index')
            ->output();
    }
    public function create(Request $request)
    {
        $quotation = $this->repository->newInstance([]);

        return $this->response->title(trans('quotation.title'))
            ->data(compact('quotation'))
            ->view('quotation.create')
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $quotation = $this->repository->create($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('quotation.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('quotation'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('quotation'))
                ->redirect();
        }
    }
    public function show(Request $request,Quotation $quotation)
    {
        if ($quotation->exists) {
            $view = 'quotation.show';
        } else {
            $view = 'quotation.create';
        }

        return $this->response->title(trans('app.view') . ' ' . trans('quotation.name'))
            ->data(compact('quotation'))
            ->view($view)
            ->output();
    }
    public function update(Request $request,Quotation $quotation)
    {
        try {
            $attributes = $request->all();

            $quotation->update($attributes);


            return $this->response->message(trans('messages.success.updated', ['Module' => trans('quotation.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('quotation'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('quotation/' . $quotation->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Quotation $quotation)
    {
        try {
            $quotation->forceDelete();

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('quotation.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('quotation'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('quotation'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('quotation.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('quotation'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('quotation'))
                ->redirect();
        }
    }
}
