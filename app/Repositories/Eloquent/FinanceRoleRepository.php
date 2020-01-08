<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\FinanceRoleRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class FinanceRoleRepository extends BaseRepository implements FinanceRoleRepositoryInterface
{


    public function boot()
    {
        $this->fieldSearchable = config('model.finance_roles.finance_role.model.search');
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return config('model.finance_roles.finance_role.model.model');
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
