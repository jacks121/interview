<?php

namespace App\Policies;

use App\User;
use App\Question;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Class QuestionPolicy
 * @package App\Policies
 */
class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function authorize(User $user, Question $question)
    {
        return $user->id == $question->user_id;
    }

    /**
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function follow(User $user, Question $question)
    {
        if ( $user->id == $question->user_id ) {
            flash('自己不能关注自己的问题', 'danger');
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the question.
     *
     * @param  \App\User $user
     * @param  \App\Question $question
     * @return mixed
     */
    public function update(User $user, Question $question)
    {
        if ( $user->id != $question->user_id ) {
            flash('您只能编辑自己发布的问题','danger');
            return false;
        }else{
            return true;
        }

    }

    /**
     * Determine whether the user can delete the question.
     *
     * @param  \App\User $user
     * @param  \App\Question $question
     * @return mixed
     */
    public function delete(User $user, Question $question)
    {
        if ( $user->id != $question->user_id ) {
            flash('您只能删除自己发布的问题','danger');
            return false;
        }

        return true;
    }

    /**
     * @param User $user
     * @param Question $question
     * @return bool
     */
    public function notSelf(User $user, Question $question)
    {
        if ( $user->id == $question->user_id ) {
            return false;
        }

        return true;
    }
}
