<?php
namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use App\Repositories\Eloquent\AirlineRepository;
use App\Repositories\Eloquent\AirportRepository;
use App\Repositories\Eloquent\OperationRepository;
use App\Repositories\Eloquent\SupplierBillItemInfoRepository;
use App\Repositories\Eloquent\SupplierBillItemRepository;
use App\Repositories\Eloquent\SupplierBillRepository;
use App\Repositories\Eloquent\SupplierBillTemplateFieldRepository;
use App\Repositories\Eloquent\SupplierRepository;
use Auth,DB;
use Illuminate\Http\Request;
use App\Models\SupplierBillItem;

/**
 * Resource controller class for user.
 */
class SupplierBillItemResourceController extends BaseController
{


    public function __construct(
        SupplierBillRepository $supplierBillRepository,
        SupplierBillItemRepository $supplierBillItemRepository,
        SupplierBillItemInfoRepository $supplierBillItemInfoRepository,
        SupplierBillTemplateFieldRepository $supplierBillTemplateFieldRepository,
        AirportRepository $airportRepository,
        AirlineRepository $airlineRepository,
        SupplierRepository $supplierRepository,
        OperationRepository $operationRepository
    )
    {
        parent::__construct();
        $this->repository = $supplierBillItemRepository;
        $this->supplierBillRepository = $supplierBillRepository;
        $this->airportRepository = $airportRepository;
        $this->airlineRepository = $airlineRepository;
        $this->supplierRepository = $supplierRepository;
        $this->supplierBillItemInfoRepository = $supplierBillItemInfoRepository;
        $this->supplierBillTemplateFieldRepository = $supplierBillTemplateFieldRepository;
        $this->operationRepository = $operationRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';

        $supplier_bill_id = $request->get('supplier_bill_id',0);
        if ($this->response->typeIs('json')) {
            $bills = $this->repository
                ->where(['supplier_id' => Auth::user()->supplier_id])
                ->where('supplier_bill_id',$supplier_bill_id);

            $bills = $bills
                ->orderBy('flight_date','asc')
                ->orderBy('id','desc')
                ->paginate($limit);

            return $this->response
                ->success()
                ->count($bills->total())
                ->data($bills->toArray()['data'])
                ->output();
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $airlines = $this->airlineRepository->orderBy('id','desc')->get();

        return $this->response->title(trans('supplier_bill_item.title'))
            ->data(compact('airports','airlines'))
            ->view('supplier_bill_item.index')
            ->output();
    }

    public function show(Request $request,SupplierBillItem $supplier_bill_item)
    {
        if ($supplier_bill_item->exists) {
            $view = 'supplier_bill_item.show';
        } else {
            $view = 'supplier_bill_item.new';
        }
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $airlines = $this->airlineRepository->orderBy('id','desc')->get();

        $supplier_bill_item_info = $this->supplierBillItemInfoRepository->where('supplier_bill_item_id',$supplier_bill_item->id)->orderBy('order','asc')->orderBy('id','asc')->get();

        $type = $request->get('type','');
        $previous_url = $type ? url()->previous() : '';

        return $this->response->title(trans('app.view') . ' ' . trans('supplier_bill_item.name'))
            ->data(compact('airports','airlines','supplier_bill_item','supplier_bill_item_info','previous_url'))
            ->view($view)
            ->output();
    }

    /**
     * Show the form for creating a new user.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $supplier_bill_item = $this->repository->newInstance([]);
        $airports = $this->airportRepository->orderBy('id','desc')->get();
        $airlines = $this->airlineRepository->orderBy('id','desc')->get();
        $fields = $this->supplierBillTemplateFieldRepository->fields(Auth::user()->supplier_id);

        if($request->get('id'))
        {
            $supplier_bill_item = $this->repository->find($request->get('id'));
            $supplier_bill_item_info = $this->supplierBillItemInfoRepository->where('supplier_bill_item_id',$supplier_bill_item->id)->orderBy('order','asc')->orderBy('id','asc')->get();

            return $this->response->title(trans('app.new') . ' ' . trans('supplier_bill_item.name'))
                ->view('supplier_bill_item.copy')
                ->data(compact('airports','airlines','supplier_bill_item','fields','supplier_bill_item_info'))
                ->output();
        }

        return $this->response->title(trans('app.new') . ' ' . trans('supplier_bill_item.name'))
            ->view('supplier_bill_item.create')
            ->data(compact('airports','airlines','supplier_bill_item','fields'))
            ->output();
    }

    /**
     * Create new user.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        try {
            $attributes = $request->all();

            $attributes['supplier_id'] = Auth::user()->supplier_id;
            $attributes['supplier_name'] = $this->supplierRepository->find($attributes['supplier_id'],['name'])->name;
            $attributes['airport_name'] = $this->airportRepository->find($attributes['airport_id'],['name'])->name;
            $attributes['airline_name'] = $this->airlineRepository->find($attributes['airline_id'],['name'])->name;

            $supplier_bill_item = $this->repository->create($attributes);

            foreach ($attributes['field'] as $key => $value)
            {
                $field = $this->supplierBillTemplateFieldRepository->find($key);
                $this->supplierBillItemInfoRepository->create([
                    'supplier_bill_item_id' => $supplier_bill_item->id,
                    'supplier_bill_template_field_id' => $field->id,
                    'field' => $field->field,
                    'field_comment' => $field->field_comment,
                    'field_value' => $value,
                    'field_mark' => $field->field_mark,
                    'order' => $field->order,
                ]);
            }
            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier_bill_item.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('supplier_bill_item'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }

    }

    /**
     * Update the user.
     *
     * @param Request $request
     * @param SupplierBillItem   $supplier_bill_item
     *
     * @return Response
     */
    public function update(Request $request, SupplierBillItem $supplier_bill_item)
    {
        try {
            $attributes = $request->all();
            $price = $attributes['price'] ?? null;
            unset($attributes['price']);
            $supplier_bill_item->update($attributes);
            /*更新油单价，全部更换*/
            if($price)
            {
                $price = bill_round($price);
                $supplier_bill_items = $this->repository->findWhere(['supplier_bill_id' => $supplier_bill_item->supplier_bill_id],['id','usg','price']);
                foreach ($supplier_bill_items as $key => $item)
                {
                    $this->repository->update([
                        'price' => $price,
                        'total' => $price * $item->usg
                    ],$item->id);
                }
            }
            if(isset($attributes['price']) || isset($attributes['usg'])|| isset($attributes['mt']))
            {
                $supplier_bill = $this->supplierBillRepository->find($supplier_bill_item->supplier_bill_id);

                $sum_data =SupplierBillItem::where('supplier_bill_id',$supplier_bill_item->supplier_bill_id)
                    ->first(
                        [
                            DB::raw('SUM(mt) as mt'),
                            DB::raw('SUM(usg) as usg'),
                            DB::raw('SUM(total) as total'),
                            'price'
                        ]
                    );
                $supplier_bill->update([
                    'mt' => $sum_data->mt,
                    'usg' => $sum_data->usg,
                    'total' => bill_round($sum_data->usg * $sum_data->price),
                    'price' => $sum_data->price,
                ]);
            }
            /*
            if(isset($attributes['field']))
            {
                foreach ($attributes['field'] as $key => $value)
                {
                    $this->supplierBillItemInfoRepository->update([
                        'field_value' => $value,
                    ],$key);
                }
            }
            */
            $url = guard_url('supplier_bill_item');
            if(isset($attributes['previous_url']) && $attributes['previous_url'])
            {
                $url = $attributes['previous_url'];
            }

            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier_bill_item.name')]))
                ->code(0)
                ->status('success')
                ->url($url)
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(url()->previous())
                ->redirect();
        }
    }

    /**
     * @param Request $request
     * @param SupplierBillItem $supplier_bill_item
     * @return mixed
     */
    public function destroy(Request $request, SupplierBillItem $supplier_bill_item)
    {
        try {

            $supplier_bill_item->forceDelete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_bill_item.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('supplier_bill_item'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_bill_item'))
                ->redirect();
        }

    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function destroyAll(Request $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_bill_item.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_bill_item'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_bill_item'))
                ->redirect();
        }
    }
}