<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\OperationRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Auth;

class OperationRepository extends BaseRepository implements OperationRepositoryInterface
{
    public function model()
    {
        return config('model.operation.operation.model');
    }
    public function createOperation($data)
    {
        $attributes = [
            'admin_id' => Auth::user()->id,
            'admin_name' => Auth::user()->name,
            'admin_model' => get_admin_model(Auth::user()),
        ];
        $attributes = array_merge($data,$attributes);
        return $this->create($attributes);
    }
}