<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\SupplierRoleRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierRoleRepository extends BaseRepository implements SupplierRoleRepositoryInterface
{


    public function boot()
    {
        $this->fieldSearchable = config('model.supplier_roles.supplier_role.model.search');
    }

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return config('model.supplier_roles.supplier_role.model.model');
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
