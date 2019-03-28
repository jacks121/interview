<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

/**
 * 用户关注
 * Class UserController
 * @package App\Http\Controllers\Api
 */
class UserFollowController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * 当前用户
     * @var
     */
    protected $user;

    /**
     * UserController constructor.
     * @param $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 关注方法
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function follow(Request $request)
    {
        $result = $this->repository->follow(apiUserId(), $request->follow);

        return returnJson(['followed' => $result]);
    }

    /**
     * 是否关注
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function isFollow(Request $request)
    {
        $result = $this->repository->isFollow(apiUserId(), $request->follow);

        return returnJson(['followed' => $result]);
    }
}
