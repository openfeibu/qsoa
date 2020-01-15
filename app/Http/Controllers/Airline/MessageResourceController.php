<?php
namespace App\Http\Controllers\Airline;

use App\Exceptions\OutputServerMessageException;
use App\Http\Controllers\Airline\ResourceController as BaseController;
use App\Models\AirlineBillItem;
use App\Models\Message;
use App\Repositories\Eloquent\MessageRepository;
use Auth;
use Illuminate\Http\Request;
use App\Models\AirlineBill;

/**
 * Resource controller class for user.
 */
class MessageResourceController extends BaseController
{


    public function __construct(
        MessageRepository $messageRepository
    )
    {
        parent::__construct();
        $this->repository = $messageRepository;
        $this->repository
            ->pushCriteria(\App\Repositories\Criteria\RequestCriteria::class);
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit',config('app.limit'));
        $search = $request->input('search',[]);

        if ($this->response->typeIs('json')) {

            $messages = $this->repository->getMessages($limit);
            return $this->response
                ->success()
                ->count($messages->total())
                ->data($messages->toArray()['data'])
                ->output();
        }

        return $this->response->title(trans('message.title'))
            ->view('message.index')
            ->output();
    }

}