@extends('layouts.app')
@include('vendor.ueditor.assets')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">私信</div>
                    <div class="card-body">
                        @foreach($messages as $group)
                                <ul class="list-unstyled mt-3">
                                    @foreach($group as $message)
                                    <li class="media mt-2">
                                        <img class="mr-3 img-border" height="50" src="{{ $message->fromUser->avatar }}">
                                        <div class="media-body">
                                            <a href="{{ route('inbox.show',$message->dialog_id) }}">
                                                <h5 class="mt-0 mb-1">{{ $message->fromUser->name }} 的来信</h5>
                                                @if($message->has_read == 'F')
                                                <span class="badge badge-pill badge-danger">有未读信件</span>
                                                @endif
                                            </a>
                                            <span class="float-right">来信时间：{{ $message->created_at }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection