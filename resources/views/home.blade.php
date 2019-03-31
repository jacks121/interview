@extends('layouts.app')
@include('vendor.ueditor.assets')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">邮箱验证</div>
                    <div class="card-body">
                        @if(Auth::check())
                            恭喜您已经成功验证了邮箱
                        @else
                            请去邮箱验证您注册的账号，即可登录。
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection