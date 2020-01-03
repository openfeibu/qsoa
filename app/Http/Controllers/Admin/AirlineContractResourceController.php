<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use App\Models\contract;
use App\Models\ContractImage;
use App\Models\Media;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\ContractRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Illuminate\Http\Request;

class AirlineContractResourceController extends BaseController
{
    public function __construct(ContractRepository $contractRepository,
                                AirlineRepository $airlineRepository,
                                SupplierRepository $supplierRepository,
                                AirportRepository $airportRepository)
    {
        parent::__construct();
        $this->repository = $contractRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->airportRepository = $airportRepository;
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
//            $data = $this->repository
//                ->setPresenter(\App\Repositories\Presenter\LinkListPresenter::class)
//                ->orderBy('order','asc')
//                ->orderBy('id','asc')
//                ->getDataTable($limit);
            $data = [
                'recordsTotal' => 0,
                'data' => [

                ],
            ];
            return $this->response
                ->success()
                ->count($data['recordsTotal'])
                ->data($data['data'])
                ->output();
        }

        return $this->response->title(trans('contract.title'))
            ->view('contract.index')
            ->output();
    }
    public function create(Request $request)
    {
        $contract = $this->repository->newInstance([]);

        $airline_id = $request->get('airline_id');
        $data = [];

        $airline = $this->airlineRepository->find($airline_id);
        $data['airline'] = $airline;

        $exist_airport = $airline->contracts;
        $exist_airport_ids = $exist_airport ? $exist_airport->pluck('airport_id')->toArray() : [];
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $data['airports'] = $airports;
        $data['contract'] = $contract;
        $data['exist_airport_ids'] = $exist_airport_ids;
        return $this->response->title(trans('contract.title'))
            ->data($data)
            ->view('contract.airline.create')
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['contractable_id'] = $attributes['airline_id'];
            $attributes['contractable_type'] = 'App\Models\Airline';

            $this->repository->createContract($attributes);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('contract.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('contract'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('contract'))
                ->redirect();
        }
    }

    public function update(Request $request,Contract $airline_contract)
    {
        try {
            $attributes = $request->all();

            $this->repository->updateContract($attributes,$airline_contract);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('contract.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('contract/' . $airline_contract->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('contract/' . $airline_contract->id))
                ->redirect();
        }
    }
    public function destroy(Request $request,Contract $contract)
    {
        try {
            $this->repository->forceDelete([$contract->id]);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('contract.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('contract'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('contract'))
                ->redirect();
        }
    }
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('contract.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('contract'))
                ->redirect();

        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('contract'))
                ->redirect();
        }
    }
}
