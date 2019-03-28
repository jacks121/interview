<?php

namespace App\Http\Controllers\Api;

use App\Answer;
use App\Http\Controllers\Controller;
use App\Question;
use App\Repositories\CommentsRepository;
use Illuminate\Http\Request;

/**
 * Class CommentsController
 * @package App\Http\Controllers\Api
 */
class CommentsController extends Controller
{
    /**
     * @var CommentsRepository
     */
    protected $repository;

    /**
     * CommentsController constructor.
     * @param $repository
     */
    public function __construct(CommentsRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getComments($id,$type)
    {
        $result = $this->repository->getComments($id,$type);

        return returnJson($result);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function commentStore(Request $request)
    {
        $result = $this->repository->createComment($request);

        return returnJson($result);
    }

    /**
     * @param $id
     * @param $type
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommentCount($type,$id)
    {
        $count = $this->repository->getCommentsCount($id,$type);

        return returnJson(['count' => $count]);
    }

}
