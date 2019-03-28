<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Message
 * @package App
 */
class Message extends Model
{
    /**
     * @var string
     */
    protected $table = 'messages';

    /**
     * @var array
     */
    protected $fillable = ['to_user_id', 'from_user_id', 'body', 'dialog_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class,'from_user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toUser()
    {
        return $this->belongsTo(User::class,'to_user_id');
    }

    /**
     * 根据dialog_id 将来信 标记已读
     * @param $dialog_id
     * @return mixed
     */
    public function markRead($dialog_id)
    {
        return $this->where([['dialog_id','=',$dialog_id],['to_user_id','=' ,apiUserId() ?? userId()]])->update([
            'has_read' => 'T',
            'read_at'=>$this->freshTimestamp()
        ]);
    }
}
