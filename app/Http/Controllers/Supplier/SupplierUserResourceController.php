<?php
namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Supplier\ResourceController as BaseController;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\SupplierUserRequest;
use App\Models\SupplierUser;
use App\Repositories\Eloquent\SupplierPermissionRepository;
use App\Repositories\Eloquent\SupplierRoleRepository;
use App\Repositories\Eloquent\SupplierUserRepository;

/**
 * Resource controller class for user.
 */
class SupplierUserResourceController extends BaseController
{

    /**
     * @var Permissions
     */
    protected $permission;

    /**
     * @var roles
     */
    protected $roles;

    /**
     * Initialize supplier_user resource controller.
     *
     * @param type SupplierUserRepository $supplier_user
     * @param type SupplierPermissionRepository $permissions
     * @param type SupplierRoleRepository $roles
     */

    public function __construct(
        SupplierUserRepository $supplier_user,
        SupplierPermissionRepository $permissions,
        SupplierRoleRepository $roles
    )
    {
        parent::__construct();
        $this->permissions = $permissions;
        $this->roles = $roles;
        $this->repository = $supplier_user;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }
    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);
        $search_name = isset($search['search_name']) ? $search['search_name'] : '';
        if ($this->response->typeIs('json')) {
            $data = $this->repository
                ->where(['supplier_id' => Auth::user()->supplier_id])
                ->setPresenter(\App\Repositories\Presenter\SupplierUserPresenter::class);
            if(!empty($search_name))
            {
                $data = $data->where(function ($query,$search_name){
                    $query->where('email','like','%'.$search_name.'%')->orWhere('phone','like','%'.$search_name.'%')->orWhere('name','like','%'.$search_name.'%');
                });
            }
            $data = $data->orderBy('id','desc')
                ->getDataTable($limit);
            return $this->response
                ->success()
                ->count($data['recordsTotal'])
                ->data($data['data'])
                ->output();
        }
        return $this->response->title(trans('app.admin.panel'))
            ->view('supplier_user.index')
            ->output();
    }

    public function show(Request $request,SupplierUser $supplier_user)
    {
        if ($supplier_user->exists) {
            $view = 'supplier_user.show';
        } else {
            $view = 'supplier_user.new';
        }
        $roles = $this->roles->all();
        return $this->response->title(trans('app.view') . ' ' . trans('supplier_user.name'))
            ->data(compact('supplier_user','roles'))
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

        $supplier_user = $this->repository->newInstance([]);
        $roles       = $this->roles->all();
        return $this->response->title(trans('app.new') . ' ' . trans('supplier_user.name'))
            ->view('supplier_user.create')
            ->data(compact('supplier_user', 'roles'))
            ->output();
    }

    /**
     * Create new user.
     *
     * @param SupplierUserRequest $request
     *
     * @return Response
     */
    public function store(SupplierUserRequest $request)
    {
        try {
            $attributes              = $request->all();
            $roles          = $request->get('roles');
            $attributes['api_token'] = str_random(60);
            $attributes['supplier_id'] = Auth::user()->supplier_id;
            $supplier_user = $this->repository->create($attributes);
            $supplier_user->roles()->sync($roles);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('supplier_user.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('supplier_user'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_user'))
                ->redirect();
        }

    }

    /**
     * Update the user.
     *
     * @param Request $request
     * @param SupplierUser   $supplier_user
     *
     * @return Response
     */
    public function update(Request $request, SupplierUser $supplier_user)
    {
        try {
            $attributes = $request->all();
            $roles          = $request->get('roles');
            $supplier_user->update($attributes);
            $supplier_user->roles()->sync($roles);
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('supplier_user.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('supplier_user'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_user/' . $supplier_user->id))
                ->redirect();
        }
    }

    /**
     * @param Request $request
     * @param SupplierUser $supplier_user
     * @return mixed
     */
    public function destroy(Request $request, SupplierUser $supplier_user)
    {
        try {

            $supplier_user->forceDelete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_user.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('supplier_user'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('supplier_user'))
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

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('supplier_user.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('supplier_user'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('supplier_user'))
                ->redirect();
        }
    }
}