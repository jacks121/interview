@extends('layouts.app')
@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <div class="auth-font">
                            作者 : {{ $question->user->name }}
                            <div class="float-right">
                                @foreach($question->topic as $topic)
                                    <span class="badge badge-primary">{{ $topic->name }}</span>
                                @endforeach
                            </div>
                        </div>
                        <h5>
                            标题：{{ $question->title }}
                            <a class="btn btn-link"
                               href="{{ route('questions.index') }}"> 更多问题...</a>
                        </h5>
                    </div>
                    <div class="card-body">
                        <p>{!! $question->body !!}</p>
                        <div class="col-md-6 float-right">
                            @if(Auth::check() && Auth::user()->can('authorize',$question))
                                <form action="{{ route('questions.destroy',$question->id) }}" method="post">
                                    <p class="text-xl-right">
                                        <a class="btn btn-link"
                                           href="{{ route('questions.edit',$question->id) }}">编辑...</a>
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                        <input type="submit" class="btn btn-link" value="删除...">
                                    </p>
                                </form>
                            @endif
                        </div>
                        <comments-form tid="{{ $question->id }}" type="question" count="{{ $question->comments_count }}"></comments-form>
                    </div>
                </div>
                <br>
                @if( $question->answers_count != 0)
                    <div class="card">
                        <div class="card-header">
                            <h5>
                                所有答案
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($question->answers as $answer)
                                <div class="card mb-3">
                                    <div class="card-header bg-transparent">
                                        <div class="auth-font">
                                            {{ $answer->user->name }}
                                            <div class="avatar">
                                                <img src="{{ $answer->user->avatar }}"
                                                     alt="{{ $answer->user->name }}"
                                                     height="28">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <p class="card-text">{!! $answer->body !!}</p>
                                    </div>
                                    <div class="card-footer bg-transparent">
                                        <comments-form tid="{{ $answer->id }}" type="answer" count="{{ $answer->comments_count }}"></comments-form>
                                    </div>
                                </div>
                                <br>
                            @endforeach
                        </div>
                    </div>
                @endif
                <br>
                @if(Auth::check())
                    <div class="card">
                        <div class="card-header">
                            有 {{ $question->answers_count }} 个回答
                        </div>
                        <div class="card-body">
                            <form action="{{ route('questions.answer',$question->id)}}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <script id="container" name="body"
                                            class="{{ $errors->has('body') ? ' is-invalid' : '' }}"
                                            type="text/plain">{{old('body')}}</script>
                                    <input id="body" type="hidden"
                                           class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('body'))
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('body') }}</strong>
                                        </span>
                                    @endif
                                    <br>
                                </div>
                                <input type="submit" class="btn btn-block btn-primary" value="回答问题">
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title text-center">
                            {{ $question->followers_count }} 人关注
                        </div>
                    </div>
                    @auth
                    <div class="card-body">
                        <div class="card-text text-center">
                            <question-follow-button question="{{ $question->id }}"></question-follow-button>
                        </div>
                    </div>
                    @endauth
                </div>
                <br>
                <div class="card">
                    <div class="card-header">
                        <div class="card-title text-center">
                            <h5>关于作者</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="media">
                            <img class="align-self-center img-border" height="50" src="{{ $question->user->avatar }}" alt="">
                            <div class="media-body">
                                <h4 class="mt-2 text-center">{{ $question->user->name }}</h4>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-4 align-content-md-center">
                                <p class="user-count">{{ $question->user->questions_count }}</p>
                                <p class="user-count">问题</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <p class="user-count">{{ $question->user->answers_count }}</p>
                                <p class="user-count">回答</p>
                            </div>
                            <div class="col-md-4 align-content-md-center">
                                <p class="user-count">{{ $question->user->followers_count }}</p>
                                <p class="user-count">关注者</p>
                            </div>
                            @if(Auth::check() && Auth::user()->can('notSelf',$question))
                                <div class="col-md-6 text-center">
                                    <user-follow-button follower="{{ Auth::guard('api')->user() }}" followeds="{{ $question->user->id }}"></user-follow-button>
                                </div>
                                <send-message-button to_user_id="{{ $question->user->id }}"></send-message-button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var ue = UE.getEditor('container', {
            toolbars: [
                ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft', 'justifycenter', 'justifyright', 'link', 'insertimage', 'fullscreen']
            ],
            elementPathEnabled: false,
            enableContextMenu: false,
            autoClearEmptyNode: true,
            wordCount: false,
            imagePopup: false,
            autotypeset: {indent: true, imageBlockLine: 'center'}
        });
        ue.ready(function () {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });
    </script>
@endsection
