<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Repositories\AnswerRepository;

class AnswersController extends Controller
{

    protected $repository;

    /**
     * AnswersController constructor.
     * @param $repository
     */
    public function __construct(AnswerRepository$repository)
    {
        $this->repository = $repository;
    }

    public function store(AnswerRequest $request, $question)
    {
        $result = $this->repository->create($request->all(), $question);
        if ( $result ) {
            flash('回答问题成功');
        } else {
            flash('回答问题失败');
        }

        return back();
    }
}
