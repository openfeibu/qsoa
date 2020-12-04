<?php

namespace App\Http\Controllers\Airline;

use App\Http\Controllers\Airline\ResourceController as BaseController;
use App\Models\Quotation;
use App\Repositories\Eloquent\QuotationRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\WorldCityRepository;
use Illuminate\Http\Request;

class QuotationResourceController extends BaseController
{
    public function __construct(QuotationRepository $quotationRepository,
        AirportRepository $airportRepository)
    {
        parent::__construct();
        $this->repository = $quotationRepository;
        $this->airportRepository = $airportRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        if ($this->response->typeIs('json')) {
            $quotations = $this->repository;

            $quotations = $quotations->orderBy('id','desc')
                ->paginate($limit);

            foreach ($quotations as $key => $quotation)
            {
                $quotation->airport_name = $quotation->airport->name;
            }
            return $this->response
                ->success()
                ->count($quotations->total())
                ->data($quotations->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('quotation.title'))
            ->data(compact('airports'))
            ->view('quotation.index')
            ->output();
    }
    public function create(Request $request)
    {
        $quotation = $this->repository->newInstance([]);
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('quotation.title'))
            ->data(compact('quotation','airports'))
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
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        return $this->response->title(trans('app.view') . ' ' . trans('quotation.name'))
            ->data(compact('quotation','airports'))
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
