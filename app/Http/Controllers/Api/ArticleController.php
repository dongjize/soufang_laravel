<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\User;
use Carbon\Carbon;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Mockery\Exception;

class ArticleController extends BaseController
{
    /**
     * 文章列表
     * @return mixed
     */
    public function index()
    {
        try {
            $list = [];
            $articles = Article::paginate(20);
            foreach ($articles as $article) {
                $author = User::find($article['author'])->first();
                $author = array(
                    'id' => $author['id'],
                    'name' => $author['name']
                );
                $list[] = [
                    'id' => $article['id'],
                    'title' => $article['title'],
                    'sub_title' => $article['sub_title'],
                    'author' => $author,
                    'is_collected' => false
                ];
            }
            $data = ['list' => $list];
        } catch (Exception $e) {
            $this->resultCode = 400;
            $this->message = 'error';
        } finally {
            return Response::json([
                'result_code' => $this->resultCode,
                'message' => $this->message,
                'data' => $data
            ]);
        }

    }

    /**
     * 单篇文章
     * @param $id
     * @return mixed
     */
    public
    function show($id)
    {
        $article = Article::find($id);
        $author = User::find($article['author'])->first();
        return view('article.show')->with('article', $article)
            ->with('author', $author['name']);
    }

    /**
     * 添加用户收藏
     * @param $id
     */
    public
    function addCollection($id)
    {
        $user = Auth::user();
        DB::table('user_collection_article')->insert([
            'user_id' => $user['id'],
            'article_id' => $id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

    /**
     * 获取用户收藏列表
     * @return mixed
     */
    public
    function getCollections()
    {
        $user = Auth::user();
        $data = DB::table('user_collection_article')->select('user_id', $user['id']);
        return Response::json([
            'result_code' => $this->resultCode,
            'message' => $this->message,
            'data' => $data
        ]);
    }
}
