<?php
/**
 * Created by PhpStorm.
 * User: joe
 * Date: 2019/3/23
 * Time: 4:58 PM
 */

namespace App\Repositories;


use App\Answer;
use App\Comments;
use App\Question;

/**
 * Class CommentsRepository
 * @package App\Repositories
 */
class CommentsRepository
{
    /**
     * @param $data
     * @return mixed
     */
    public function createComment($data)
    {
        $model = $this->getModelByType($data->type);

        $model  = $model::find($data->id);
        $result = $model->comments()->create([
            'user_id' => apiUserId() ?? userId(),
            'body'    => $data->body,
        ]);

        $model->increment('comments_count');

        return $result;
    }

    /**
     * @param $id
     * @param $type
     * @return mixed
     */
    public function getComments($id,$type)
    {
        $model = $this->getModelByType($type);
        $answer = $model::with('comments', 'comments.user')->where('id', $id)->first();

        return $answer->comments;
    }

    public function getCommentsCount($id, $type)
    {
        $model = $this->getModelByType($type);

        return $model::find($id)->comments_count;
    }

    /**
     * @param $type
     * @return string
     */
    private function getModelByType($type)
    {
        return $type === 'answer' ? new Answer() : new Question();
    }
}