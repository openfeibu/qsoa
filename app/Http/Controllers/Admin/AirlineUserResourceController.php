<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests\AirlineUserRequest;
use App\Models\AirlineUser;
use App\Models\Airline;
use App\Repositories\Eloquent\AirlinePermissionRepository;
use App\Repositories\Eloquent\AirlineRoleRepository;
use App\Repositories\Eloquent\AirlineUserRepository;

/**
 * Resource controller class for user.
 */
class AirlineUserResourceController extends BaseController
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
     * Initialize airline_user resource controller.
     *
     * @param type AirlineUserRepository $airline_user
     * @param type AirlinePermissionRepository $permissions
     * @param type AirlineRoleRepository $roles
     */

    public function __construct(
        AirlineUserRepository $airline_user,
        AirlinePermissionRepository $permissions,
        AirlineRoleRepository $roles
    )
    {
        parent::__construct();
        $this->permissions = $permissions;
        $this->roles = $roles;
        $this->repository = $airline_user;
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
                ->setPresenter(\App\Repositories\Presenter\AirlineUserPresenter::class);

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
        return $this->response->title(trans('airline_user.title'))
            ->view('airline_user.index')
            ->output();
    }

    public function show(Request $request,AirlineUser $airline_user)
    {
        if ($airline_user->exists) {
            $view = 'airline_user.show';
        } else {
            $view = 'airline_user.new';
        }
        $roles = $this->roles->all();

        $airlines = Airline::orderBy('id','desc')->get();

        return $this->response->title(trans('app.view') . ' ' . trans('airline_user.name'))
            ->data(compact('airline_user','roles','airlines'))
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

        $airline_user = $this->repository->newInstance([]);
        $roles       = $this->roles->all();
        $airlines = Airline::orderBy('id','desc')->get();

        return $this->response->title(trans('app.new') . ' ' . trans('airline_user.name'))
            ->view('airline_user.create')
            ->data(compact('airline_user', 'roles','airlines'))
            ->output();
    }

    /**
     * Create new user.
     *
     * @param AirlineUserRequest $request
     *
     * @return Response
     */
    public function store(AirlineUserRequest $request)
    {
        try {
            $attributes              = $request->all();
            $roles          = $request->get('roles');
            $attributes['api_token'] = str_random(60);

            $airline_user = $this->repository->create($attributes);
            $airline_user->roles()->sync($roles);
            return $this->response->message(trans('messages.success.created', ['Module' => trans('airline_user.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('airline_user'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_user'))
                ->redirect();
        }

    }

    /**
     * Update the user.
     *
     * @param Request $request
     * @param AirlineUser   $airline_user
     *
     * @return Response
     */
    public function update(Request $request, AirlineUser $airline_user)
    {
        try {
            $attributes = $request->all();
            $roles          = $request->get('roles');
            $airline_user->update($attributes);
            $airline_user->roles()->sync($roles);
            return $this->response->message(trans('messages.success.updated', ['Module' => trans('airline_user.name')]))
                ->code(0)
                ->status('success')
                ->url(guard_url('airline_user/'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_user/' . $airline_user->id))
                ->redirect();
        }
    }

    /**
     * @param Request $request
     * @param AirlineUser $airline_user
     * @return mixed
     */
    public function destroy(Request $request, AirlineUser $airline_user)
    {
        try {

            $airline_user->forceDelete();
            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline_user.name')]))
                ->http_code(201)
                ->status('success')
                ->url(guard_url('airline_user'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->http_code(400)
                ->status('error')
                ->url(guard_url('airline_user'))
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

            return $this->response->message(trans('messages.success.deleted', ['Module' => trans('airline_user.name')]))
                ->status("success")
                ->http_code(201)
                ->url(guard_url('airline_user'))
                ->redirect();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->http_code(400)
                ->url(guard_url('airline_user'))
                ->redirect();
        }
    }
}