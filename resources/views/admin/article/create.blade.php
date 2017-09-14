@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">新增Article</div>

                    <div class="panel-body">
                        <form action="{{ URL('admin/articles') }}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="text" name="title" class="form-control" required="required">
                            <br>
                            <input type="text" name="sub_title" class="form-control" required="required">
                            <br>
                            <textarea name="body" rows="10" class="form-control" required="required"></textarea>
                            <br>
                            <button class="btn btn-lg btn-info">新增Article</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection