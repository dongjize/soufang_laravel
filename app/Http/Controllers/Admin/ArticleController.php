<?php

namespace App\Http\Controllers\Admin;

use App\Article;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::paginate(25);
        return view('admin.article.index')->with('articles', $articles);
    }

    public function create()
    {
        return view('admin.article.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:63',
            'sub_title' => 'required|max:127',
            'content' => 'required'
        ]);
        $article = new Article;
        $article->title = Input::get('title');
        $article->sub_title = Input::get('sub_title');
        $article->content = Input::get('content');

        if ($article->save()) {
            return Redirect::to('admin');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败!');
        }
    }

    public function edit($id)
    {
        $article = Article::find($id);
        return view('admin.article.edit')->with('article', $article);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:63',
            'sub_title' => 'required|max:127',
            'content' => 'required'
        ]);
        $article = Article::find($id);
        $article->title = Input::get('title');
        $article->sub_title = Input::get('sub_title');
        $article->content = Input::get('content');
        if ($article->save()) {
            return Redirect::to('admin');
        } else {
            return Redirect::back()->withInput()->withErrors('保存失败!');
        }
    }

    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();
        return Redirect::to('admin');
    }


}
