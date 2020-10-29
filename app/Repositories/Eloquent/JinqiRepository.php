<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\JinqiRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class JinqiRepository extends BaseRepository implements JinqiRepositoryInterface
{
    public function model()
    {
        return config('model.jinqi.jinqi.model');
    }

}