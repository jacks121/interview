@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        问题列表
                        <a class="btn btn-link float-right"
                           href="{{ route('questions.create') }}">我要提问</a>
                    </div>
                    <div class="card-body">
                        @foreach($questions as $question)
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <a class="text-muted" href="{{ route('questions.show',$question->id) }}">
                                        <h4 class="panel-title">{{ $question->title }}</h4>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <br>
        {{ $questions->links() }}
    </div>
@endsection