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

class SupplierContractResourceController extends BaseController
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

    public function create(Request $request)
    {
        $contract = $this->repository->newInstance([]);

        $supplier_id = $request->get('supplier_id');
        $data = [];

        $supplier = $this->supplierRepository->find($supplier_id);
        $data['supplier'] = $supplier;

        $exist_airport = $supplier->contracts;
        $exist_airport_ids = $exist_airport ? $exist_airport->pluck('airport_id')->toArray() : [];
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $data['airports'] = $airports;
        $data['contract'] = $contract;
        $data['exist_airport_ids'] = $exist_airport_ids;
        return $this->response->title(trans('contract.title'))
            ->data($data)
            ->view('contract.supplier.create')
            ->output();
    }
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['contractable_id'] = $attributes['supplier_id'];
            $attributes['contractable_type'] = 'App\Models\Supplier';

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

    public function update(Request $request,Contract $supplier_contract)
    {
        try {
            $attributes = $request->all();

            $this->repository->updateContract($attributes,$supplier_contract);

            return $this->response->message(trans('messages.success.created', ['Module' => trans('contract.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('contract/' . $supplier_contract->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('contract/' . $supplier_contract->id))
                ->redirect();
        }
    }
}
