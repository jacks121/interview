@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">站内消息</div>

                    <div class="card-body">
                        @foreach($user->notifications->sortByDesc('created_at') as $notification)
                            <li>
                            @include('notification.'.snake_case(class_basename($notification->type))) <div class="float-right">{{ $notification->created_at }}</div>
                            </li>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
