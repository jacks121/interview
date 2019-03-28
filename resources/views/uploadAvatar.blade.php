@extends('layouts.app')
@include('vendor.ueditor.assets')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <upload-image user_avatar="{{ user()->avatar }}"></upload-image>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection