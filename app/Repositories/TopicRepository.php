<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/16
 * Time: 3:21 PM
 */

namespace App\Repositories;


use App\Topic;

/**
 * Class TopicRepository
 * @package App\Repositories
 */
class TopicRepository
{
    /**
     * @param $id
     * @param int $num
     */
    public function incrementQuestionCount($id, $num = 1)
    {
        Topic::find($id)->increment('questions_count',$num);
    }
}