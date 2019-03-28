<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/18
 * Time: 11:14 AM
 */

namespace App\Repositories;


use App\Answer;
use Facades\App\Question;

class AnswerRepository
{
    public function create(array $attributes, $question_id)
    {
        $fill_data = array_merge($attributes, [
            'question_id' => $question_id,
            'user_id'     => userId(),
        ]);
        $result = Answer::create($fill_data);
        $question = Question::RfindAll($question_id);
        $question->RIncrement('answers_count');

        return $result;
    }
}