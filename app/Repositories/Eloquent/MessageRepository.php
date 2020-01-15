<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\MessageRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;
use Auth;
use App\Models\Message;

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
    public function getMessages($limit)
    {
        $messages = Message::where('admin_group',get_admin_model(Auth::user()))
            ->where(function ($query) {
                $query->where('admin_id',Auth::user()->id)->orWhere('admin_id',0);
            })
            ->orderBy('id','desc')
            ->paginate($limit);
        foreach ($messages as $key => $message)
        {
            Message::where('id',$message->id)->update(['read' => 1]);
        }
        return $messages;
    }
    public function unReadCount()
    {
        $count = $this->model->where('admin_group',get_admin_model(Auth::user()))
            ->where(function ($query) {
                $query->where('admin_id',Auth::user()->id)->orWhere('admin_id',0);
            })
            ->where('read',0)
            ->count();

        return $count;
    }
}