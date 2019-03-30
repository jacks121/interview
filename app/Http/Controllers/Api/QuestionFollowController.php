<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\QuestionRepository;
use Illuminate\Http\Request;

/**
 * Class QuestionController
 * @package App\Http\Controllers\Api
 */
class QuestionFollowController extends Controller
{
    protected $repository;

    protected $user;

    /**
     * QuestionController constructor.
     * @param $repository
     */
    public function __construct(QuestionRepository $repository)
    {
        $this->repository = $repository;
        $this->user = apiUserId();
    }

    /**
     * 是否关注
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isFollow(Request $request)
    {
        $result = $this->repository->isFollow($request->question, $this->user);

        return returnJson(['followed' => $result]);
    }

    /**
     * 关注问题
     * @param Request $request ->question question_id
     * @param Request $request ->user user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(Request $request)
    {
        $result = $this->repository->follow($request->question, $this->user);
	
        return returnJson(['followed' => $result]);
    }
}
