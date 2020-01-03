<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\AirlineRoleRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class AirlineRoleRepository extends BaseRepository implements AirlineRoleRepositoryInterface
{


    public function boot()
    {
        $this->fieldSearchable = config('model.airline_roles.airline_role.model.search');
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return config('model.airline_roles.airline_role.model.model');
    }

    /**
     * Find a user by its key.
     *
     * @param type $key
     *
     * @return type
     */
    public function findRoleBySlug($key)
    {
        return $this->model->whereSlug($key)->first();
    }
}
