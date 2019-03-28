<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/16
 * Time: 3:09 PM
 */

namespace App\Repositories;


use Facades\App\Question;
use App\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class QuestionRepository
 * @package App\Repositories
 */
class QuestionRepository
{
    /**
     * @param $id
     * @return mixed
     */
    public function byId($id)
    {
        return Question::RFindAll($id);
    }

    /**
     * @return mixed
     */
    public function getAll()
    {
        return Question::published()->with('answers')->latest('updated_at')->paginate(12);
    }


    /**
     * @param array $attributes
     * @param User $user
     * @return mixed
     */
    public function create(array $attributes, User $user)
    {
        $user->increment('questions_count');

        return Question::create($attributes);
    }

    /**
     * @param array $attributes
     * @param $id
     * @return bool
     */
    public function save(array $attributes, $id)
    {
        $question = Question::find($id);
        if ( Auth::user()->can('update', $question) ) {
            $question->topic()->sync($attributes['topics'] ?? []);
            $question->update($attributes);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function del($id)
    {
        $question = Question::find($id);
        if ( Auth::user()->can('delete', $question) ) {
            return $question->delete();
        }

        return false;
    }

    /**
     * @param $question_id
     * @return bool
     */
    public function follow($question_id, $user_id)
    {
        $question = Question::find($question_id);
        $result   = $question->followThis($user_id);
        $this->updateFollowersCount($question, !empty($result['attached']));

        return $result;
    }

    /**
     * @param Question $question
     * @param $state
     * @return int
     */
    private function updateFollowersCount(Question $question, $state)
    {
        if ( $state ) {
            return $question->increment('followers_count');
        } else {
            return $question->decrement('followers_count');
        }
    }

    /**
     * @param $question_id
     * @param $user_id
     * @return mixed
     */
    public function isFollow($question_id, $user_id)
    {
        $question = Question::find($question_id);

        return $question->isFollow($user_id);
    }

}