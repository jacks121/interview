<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuestionRequest;
use App\Repositories\QuestionRepository;
use App\Repositories\TopicRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;


/**
 * Class QuestionsController
 * @package App\Http\Controllers
 * @property \App\User $user
 */
class QuestionsController extends Controller
{
    /**
     * @var QuestionRepository
     */
    protected $question_repository;

    /**
     * @var TopicRepository
     */
    protected $topic_repository;

    /**
     * QuestionsController constructor.
     * @param QuestionRepository $questionRepository
     * @param TopicRepository $topicRepository
     */
    public function __construct(QuestionRepository $questionRepository, TopicRepository $topicRepository)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->question_repository = $questionRepository;
        $this->topic_repository    = $topicRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = $this->question_repository->getAll();

        return view('questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\QuestionRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionRequest $request)
    {
        $topics = $request->topics;
        $user   = Auth::user();
        collect($topics)->map(function ($topic) {
            $this->topic_repository->incrementQuestionCount($topic);
        });

        $data = array_merge(['user_id' => $user->id], $request->all());

        $question = $this->question_repository->create($data, $user);

        $question->topic()->attach($topics);

        return redirect(route('questions.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = $this->question_repository->byId($id);

        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = $this->question_repository->byId($id);

        if ( Auth::user()->can('update', $question) ) {
            return view('questions.edit', compact('question'));
        }

        return back();
    }

    /**
     * @param QuestionRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(QuestionRequest $request, $id)
    {
        $result = $this->question_repository->save($request->all(), $id);
        if ( $result ) {
            flash('修改成功', 'success');

            return redirect()->route('questions.show', $id);
        } else {
            flash('修改失败', 'danger');

            return redirect()->route('questions.show', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->question_repository->del($id);
        if ( $result ) {
            flash('删除成功！', 'success');

            return redirect()->route('questions.index');
        }

        return back();
    }
}
