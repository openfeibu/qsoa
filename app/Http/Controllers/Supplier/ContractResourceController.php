<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Models\Airline;
use App\Models\contract;
use App\Models\ContractImage;
use App\Models\Media;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\ContractRepository;
use App\Repositories\Eloquent\MediaRepository;
use App\Repositories\Eloquent\SupplierRepository;
use App\Services\UploadsManagerService;
use Illuminate\Http\Request;

class ContractResourceController extends BaseController
{
    public function __construct(ContractRepository $contractRepository,
                                AirlineRepository $airlineRepository,
                                SupplierRepository $supplierRepository,
                                AirportRepository $airportRepository,
                                MediaRepository $mediaRepository,
                                UploadsManagerService $managerService)
    {
        parent::__construct();
        $this->repository = $contractRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->airportRepository = $airportRepository;
        $this->mediaRepository = $mediaRepository;
        $this->manager = $managerService;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        if ($this->response->typeIs('json')) {
            $contracts = $this->repository
                ->where('contractable_type',config('model.supplier.supplier.model'))
                ->orderBy('airport_id','desc')
                ->orderBy('id','desc')
                ->paginate($limit);
            foreach ($contracts as $key => $contract)
            {
                $contract->airport_name = $contract->airport->name;
                $contract->contract_partner = $contract->contractable->name;
            }
            return $this->response
                ->success()
                ->count($contracts->total())
                ->data($contracts->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('contract.title'))
            ->view('contract.index')
            ->output();
    }
    public function create(Request $request)
    {
        $contract = $this->repository->newInstance([]);

        $type = $request->get('type','');
        $id = $request->get('id');
        $data = [];
        if($type == 'airline')
        {
            $view = 'airline';
            $airline = $this->airlineRepository->find($id);
            $data['airline'] = $airline;
        }else{
            $view = 'supplier';
            $supplier = $this->supplierRepository->find($id);
            $data['supplier'] = $supplier;
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $data['airports'] = $airports;
        $data['contract'] = $contract;
        return $this->response->title(trans('contract.title'))
            ->data($data)
            ->view('contract.'.$view.'.create')
            ->output();
    }
    public function store(Request $request)
    {
        $attributes = $request->all();
        if($attributes['type'] == 'airline')
        {
            $attributes['contractable_id'] = $attributes['airline_id'];
            $attributes['contractable_type'] = 'App\Models\Airline';
        }else{
            $attributes['contractable_id'] = $attributes['supplier_id'];
            $attributes['contractable_type'] = 'App\Models\Supplier';
        }

        try {
            $images = $attributes['image'];
            $date_arr = explode('~',$attributes['date']);
            $attributes['start_time'] = trim($date_arr[0]);
            $attributes['end_time'] = trim($date_arr[1]);

            $contract = $this->repository->create($attributes);

            foreach ($images as $image)
            {
                $contract_image = ContractImage::create([
                    'url' => $image,
                    'contract_id' => $contract->id
                ]);
                Media::where('url',$image)->update([
                    'mediaable_id' => $contract->id,
                    'mediaable_type' => 'App\Models\Contract'
                ]);
            }

            return $this->response->message(trans('messages.success.created', ['Module' => trans('contract.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('contract/' . $contract->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('contract'))
                ->redirect();
        }
    }
    public function show(Request $request,Contract $contract)
    {
        $contractable = $contract->contractable;
        $airport = $this->airportRepository->find($contract->airport_id);

        $data['contractable'] = $contractable;
        $data['airport'] = $airport;
        $data['contract'] = $contract;
        return $this->response->title(trans('app.view') . ' ' . trans('contract.name'))
            ->data($data)
            ->view('contract.show')
            ->output();
    }
    public function update(Request $request,Contract $contract)
    {
        try {
            $attributes = $request->all();

            $this->repository->updateContract($attributes,$contract);

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('contract.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('contract'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('contract/' . $contract->id))
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
    public function destroyImage(Request $request)
    {
        try{
            $url = $request->get('url');
            ContractImage::where('url',$url)->delete();

            $this->mediaRepository->deleteMedia($url);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('contract.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('contract'))
                ->redirect();

        }catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('contract'))
                ->redirect();
        }
    }
}
