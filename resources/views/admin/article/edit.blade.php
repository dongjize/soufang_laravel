@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-lg-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">编辑文章</div>

                    <div class="panel-body">
                        <form action="{{ URL('admin/articles/').$article->id }}" method="post">
                            <input name="_method" type="hidden" value="PUT">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            文章标题: <input type="text" name="title" class="form-control" required="required" value="{{ $article->title }}">
                            <br>
                            文章简介: <input type="text" name="sub_title" class="form-control" required="required" value="{{ $article->sub_title }}">
                            <br>
                            文章正文: <textarea name="content" rows="10" class="form-control" required="required">{{ $article->content }}</textarea>
                            <br>
                            <button class="btn btn-lg btn-info">提交修改</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection