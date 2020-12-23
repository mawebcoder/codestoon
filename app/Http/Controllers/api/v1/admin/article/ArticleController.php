<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    use RefreshDatabase;

    public $empty_success_message = [
        'message' => 'success',
        'data' => null
    ];
    public $failed_message = [
        'message' => 'success',
        'data' => null
    ];

    public function __construct()
    {

    }

    public function index()
    {

    }


    public function store(Request $request)
    {
        $data = $request->only(
            [
                'fa_title',
                'en_title',
                'short_description',
                'meta'
            ]
        );
        $data['content'] = $request->text;
        $data['user_id'] = Auth::id();
        $article = Article::create($data);
        if ($article) {
            $result = $article->articleCategories()->sync($request->article_categories);
            return $result ?
                response()->json($this->empty_success_message, 201) :
                response()->json($this->failed_message, 500);
        }
        return response()->json($this->failed_message, 500);
    }

    public function destroy(Article $article)
    {
        $result = $article->delete();
        return $result ?
            response()->json($this->empty_success_message) :
            response()->json($this->failed_message, 500);
    }

    public function update(Article $article)
    {
        $data = request()->only([
            'meta',
            'fa_title',
            'en_title',
            'short_description',
        ]);
        $data['content'] = request()->text;

        $article->update($data);

        $article->articleCategories()->sync(request()->article_categories);

        return response()->json($this->empty_success_message);
    }

    public function deleteMultipleArticle()
    {
        $ids = request()->ids;
        $result = Article::whereIn('id', $ids)->delete();

        return $result ?
            response()->json($this->empty_success_message) :
            response()->json($this->failed_message);

    }

}
