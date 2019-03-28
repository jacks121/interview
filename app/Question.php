<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 * @package App
 */
class Question extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'body',
        'user_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function topic()
    {
        return $this->belongsToMany(Topic::class)->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return $this->is_hidden === 'T';
    }

    /**
     * @return bool
     */
    public function isCloseComment()
    {
        return $this->close_comment === 'T';
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopePublished($query)
    {
        return $query->where('is_hidden', 'F');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }


    /**
     * 这个问题被哪些用户关注了
     * @return $this
     */
    public function follows()
    {
        return $this->belongsToMany(User::class, 'user_question')->withTimestamps();
    }

    /**
     * 关注这个问题
     * @param $user
     * @return mixed
     */
    public function followThis($user)
    {
        return $this->follows()->toggle($user);
    }

    /**
     * 是否关注了某个问题
     * @param $user user_id
     * @return mixed
     */
    public function isFollow($user)
    {
        return ! !$this->follows()->where('user_id', $user)->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(Comments::class, 'commentable');
    }

    /**
     * 查找和question相关的所有数据 并更新redis
     * @param $id
     * @return mixed
     */
    public function RFindAll($id)
    {
        $question = getRedisQuestions($id);
        if ( !$question ) {
            $question = $this->with(['topic', 'user', 'answers'])->find($id);
            pushRedisQuestions($id, $question);
        }

        return $question;
    }

    /**
     * increment的同事更新redis
     * @param $field
     * @param int $step
     * @return int
     */
    public function RIncrement($field, $step = 1)
    {
        $this->increment($field,$step);
        pushRedisQuestions($this->id, $this);
    }

}
