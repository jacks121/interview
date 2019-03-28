<?php

namespace App\Http\Controllers;

use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Auth;

/**
 * Class InboxController
 * @package App\Http\Controllers
 */
class InboxController extends Controller
{
    /**
     * @var MessageRepository
     */
    protected $message;

    /**
     * InboxController constructor.
     * @param $message
     */
    public function __construct(MessageRepository $message)
    {
        $this->message = $message;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $messages = Auth::user()->messages->sortByDesc('created_at')->unique('dialog_id')->groupBy('dialog_id');
        return view('Inbox.index', compact('messages'));
    }

    /**
     * @param $dialog_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($dialog_id)
    {
        $this->message->markReadByDialogId($dialog_id);
        $messages = $this->message->getMessagesByDialogId($dialog_id);

        return view('Inbox.show',compact('messages'));
    }

    /**
     * @param $dialog_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($dialog_id)
    {
        $to_user_id = $this->getToUserId($dialog_id);
        $this->message->sendMessage([
            'to_user_id'=>$to_user_id,
            'body'=>request('body')
        ]);
        return back();
    }

    /**
     * @param $dialog_id
     * @return string
     */
    private function getToUserId($dialog_id)
    {
        $to_user_id = '';
        foreach (explode('|',$dialog_id) as $id) {
            $id != userId() && $to_user_id = $id;
        }
        return $to_user_id;
    }
}
