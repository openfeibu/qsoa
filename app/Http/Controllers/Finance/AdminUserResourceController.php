<?php
namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Finance\ResourceController as BaseController;
use App\Models\FinanceUser;
use App\Repositories\Eloquent\PermissionRepositoryInterface;
use App\Repositories\Eloquent\RoleRepositoryInterface;
use App\Http\Requests\FinanceUserRequest;
use App\Repositories\Eloquent\FinanceUserRepositoryInterface;

/**
 * Resource controller class for user.
 */
class FinanceUserResourceController extends BaseController
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
     * Initialize finance_user resource controller.
     *
     * @param type FinanceUserRepositoryInterface $finance_user
     * @param type PermissionRepositoryInterface $permissions
     * @param type RoleRepositoryInterface $roles
     */

    public function __construct(
        FinanceUserRepositoryInterface $finance_user,
        PermissionRepositoryInterface $permissions,
        RoleRepositoryInterface $roles
    )
    {
        parent::__construct();
        $this->permissions = $permissions;
        $this->roles = $roles;
        $this->repository = $finance_user;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class)
            ->pushCriteria(\App\Repositories\Criteria\FinanceUserResourceCriteria::class);
    }
    public function index(FinanceUserRequest $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search_name','');
        if ($this->response->typeIs('json')) {
            $data = $this->repository
                ->setPresenter(\App\Repositories\Presenter\FinanceUserPresenter::class);
            if(!empty($search_name))
            {
                $data = $data->where(function ($query,$search_name){
                    $query->where('email','like','%'.$search_name.'%');
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
            ->view('finance_user.index')
            ->output();
    }

    public function show(FinanceUserRequest $request,FinanceUser $finance_user)
    {
        if ($finance_user->exists) {
            $view = 'finance_user.show';
        } else {
            $view = 'finance_user.new';
        }
        $roles = $this->roles->all();
        return $this->response->title(trans('app.view') . ' ' . trans('finance_user.name'))
            ->data(compact('finance_user','roles'))
            ->view($view)
            ->output();
    }

    /**
     * Show the form for creating a new user.
     *
     * @param FinanceUserRequest $request
     *
     * @return Response
     */
    public function create(FinanceUserRequest $request)
    {

        $finance_user = $this->repository->newInstance([]);
        $roles       = $this->roles->all();
        return $this->response->title(trans('app.new') . ' ' . trans('finance_user.name'))
            ->view('finance_user.create')
            ->data(compact('finance_user', 'roles'))
            ->output();
    }

    /**
     * Create new user.
     *
     * @param FinanceUserRequest $request
     *
     * @return Response
     */
    public function store(FinanceUserRequest $request)
    {
        try {
            $attributes              = $request->all();
            $roles          = $request->get('roles');
            $attributes['user_id']   = user_id();
            $attributes['user_type'] = user_type();
            $attributes['api_token'] = str_random(60);
            $finance_user = $this->repository->create($attributes);
            $finance_user->roles()->sync($roles);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('finance_user.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('finance_user'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('finance_user/' . $finance_user->id))
                ->redirect();
        }

    }

    /**
     * Update the user.
     *
     * @param FinanceUserRequest $request
     * @param FinanceUser   $finance_user
     *
     * @return Response
     */
    public function update(FinanceUserRequest $request, FinanceUser $finance_user)
    {
        try {
            $attributes = $request->all();
            $roles          = $request->get('roles');
            $finance_user->update($attributes);
            $finance_user->roles()->sync($roles);
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('finance_user.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('finance_user/' . $finance_user->id))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('finance_user/' . $finance_user->id))
                ->redirect();
        }
    }

    /**
     * @param FinanceUserRequest $request
     * @param FinanceUser $finance_user
     * @return mixed
     */
    public function destroy(FinanceUserRequest $request, FinanceUser $finance_user)
    {
        try {

            $finance_user->forceDelete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('finance_user.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('finance_user'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('finance_user/' . $finance_user->id))
                ->redirect();
        }

    }

    /**
     * @param FinanceUserRequest $request
     * @return mixed
     */
    public function destroyAll(FinanceUserRequest $request)
    {
        try {
            $data = $request->all();
            $ids = $data['ids'];
            $this->repository->forceDelete($ids);

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('finance_user.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('finance_user'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('finance_user'))
                ->redirect();
        }
    }
}