@extends('layouts.app')
@include('vendor.ueditor.assets')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">对话列表</div>
                    <div class="card-body">
                        <form action="{{ route('inbox.store',$messages->first()->dialog_id) }}">
                            <div class="form-group mb-5">
                                <textarea name="body" id="" cols="30" rows="3" class="form-control"></textarea>
                                <input type="submit" value="发送私信" class="btn btn-success float-right mt-1">
                            </div>
                        </form>
                        <message-list dialog_id="{{ $messages->first()->dialog_id }}"></message-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection