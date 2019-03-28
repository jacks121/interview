<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/21
 * Time: 10:46 AM
 */

namespace App\Repositories;


use App\Message;
use App\Notifications\SendMessageNotification;
use App\User;

/**
 * Class MessageRepository
 * @package App\Repositories
 */
class MessageRepository
{
    /**
     * @var Message
     */
    protected $message;

    /**
     * MessageRepository constructor.
     * @param $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function sendMessage($params)
    {
        $user   = User::find($params['to_user_id']);
        $result = $this->message->create([
            'to_user_id'   => $params['to_user_id'],
            'from_user_id' => apiUserId() ?? userId(),
            'body'         => $params['body'],
            'dialog_id'    => $this->buildDialogId($params['to_user_id']),
        ]);
        $user->notify(new SendMessageNotification($params['body']));

        return $result;

    }

    /**
     * @param string $to_user_id
     * @return array|string
     */
    public function buildDialogId($to_user_id)
    {
        $from_user_id = apiUserId() ?? userId();
        $id           = [$to_user_id, $from_user_id];
        sort($id);
        $id = implode('|', $id);

        return $id;
    }

    /**
     * @param $dialog_id
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getMessagesByDialogId($dialog_id, $offset = 0, $limit = 10)
    {
        return $this->message->where('dialog_id', $dialog_id)->with('fromUser')->orderBy('created_at', 'desc')->offset($offset)
                      ->limit($limit)->get();
    }

    /**
     * @param $dialog_id
     * @return mixed
     */
    public function markReadByDialogId($dialog_id)
    {
        return $this->message->markRead($dialog_id);
    }
}