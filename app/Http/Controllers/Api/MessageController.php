<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $repository;

    /**
     * MessageController constructor.
     * @param $repository
     */
    public function __construct(MessageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function sendPrivateMessage(Request $request)
    {
        return returnJson($this->repository->sendMessage($request->all()));
    }

    public function index($dialog_id, $offset = 0, $limit = 10)
    {
        return returnJson($this->repository->getMessagesByDialogId($dialog_id,$offset,$limit));
    }
}
