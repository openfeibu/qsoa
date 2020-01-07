<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MessageRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    public function model()
    {
        return config('model.message.message.model');
    }
    public function createMessage($attributes)
    {
        $attributes['admin_id'] = $attributes['admin_id'] ?? 0;
        $attributes['airline_id'] = $attributes['airline_id'] ?? 0;
        $attributes['supplier_id'] = $attributes['supplier_id'] ?? 0;

        return $this->create($attributes);
    }
}