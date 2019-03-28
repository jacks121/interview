@extends('layouts.app')
@include('vendor.ueditor.assets')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $.getJSON('api/test',function(data){console.log(data)});
        $(document).ready(function(){
            axios.get('api/test').then(response => {
                console.log(response.data);
            });
        });

    </script>
@endsection