@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">文章列表</div>

                    <div class="panel-body">
                        <div class="table table-striped">
                            <tr class="row">
                                <th class="col-lg-4">标题</th>
                                <th class="col-lg-1">编辑</th>
                                <th class="col-lg-2">删除</th>
                            </tr>

                            {{--@foreach($articles as $article)--}}
                                {{--<tr class="row">--}}
                                    {{--<td class="col-lg-2">--}}
                                        {{--{{ $article->title }}--}}
                                    {{--</td>--}}
                                    {{--<td class="col-lg-4">--}}
                                        {{--{{ $article->sub_title }}--}}
                                    {{--</td>--}}
                                {{--</tr>--}}
                            {{--@endforeach--}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection