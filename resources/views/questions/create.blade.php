@extends('layouts.app')
@section('content')
    @include('vendor.ueditor.assets')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        发布问题
                        <a class="btn btn-link"
                           href="{{ route('questions.index') }}">Index...</a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('questions.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label for="title" class="col-form-label">提问标题</label>

                                <input id="title" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" name="title" value="{{ old('title') }}">
                                @if ($errors->has('title'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            <label for="body" class="col-form-label">提问内容</label>
                            </div>
                            <div class="form-group">
                                <select class="js-data-example-ajax form-control" name="topics[]" multiple="multiple"></select>
                            </div>
                            <div class="form-group">
                            <script id="container" name="body" class="{{ $errors->has('body') ? ' is-invalid' : '' }}" type="text/plain">
                                {{old('body')}}
                            </script>
                            <input id="body" type="hidden" class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}">
                            @if ($errors->has('body'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('body') }}</strong>
                                </span>
                            @endif
                            <br>
                            </div>
                            <input type="submit" class="btn btn-block btn-primary" value="发布问题">

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        var ue = UE.getEditor('container',{
            toolbars: [
                ['bold', 'italic', 'underline', 'strikethrough', 'blockquote', 'insertunorderedlist', 'insertorderedlist', 'justifyleft','justifycenter', 'justifyright',  'link', 'insertimage', 'fullscreen']
            ],
            elementPathEnabled: false,
            enableContextMenu: false,
            autoClearEmptyNode:true,
            wordCount:false,
            imagePopup:false,
            autotypeset:{ indent: true,imageBlockLine: 'center' }
        });
        ue.ready(function() {
            ue.execCommand('serverparam', '_token', '{{ csrf_token() }}'); // 设置 CSRF token.
        });

        $(document).ready(function() {
            $('.js-data-example-ajax').select2({
                ajax: {
                    url: '/api/topic',
                    processResults: function (data) {
                        // Tranforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.data
                        };
                    }
                }
            });
        });
    </script>
@endsection
