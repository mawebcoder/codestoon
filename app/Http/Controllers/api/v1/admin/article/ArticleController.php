<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {

    }


    public function store(Request $request)
    {
        $article = Article::create([
            'fa_title' => $request->fa_title,
            'en_title' => $request->en_title ?? null,
            'user_id' => auth()->user()->id,
            'short_description' => $request->short_description,
            'content' => $request->text,
            'meta' => $request->meta
        ]);
        if ($article) {
            $result = $article->articleCategories()->sync($request->article_categories);
            if ($result) {
                return response()->json([
                    'message' => 'success',
                    'data' => null
                ], 201);
            }
            return response()->json([
                'message' => 'failed',
                'data' => null
            ], 500);
        }
        return response()->json([
            'message' => 'failed',
            'data' => null
        ], 500);
    }

    public function destroy(Article $article)
    {
        $result = $article->delete();
        if ($result) {
            return response()->json([
                'message' => 'success',
                'data' => null
            ]);
        }
        return response()->json([
            'data' => null,
            'message' => 'failed'
        ],500);
    }

    public function update(Article $article)
    {

    }

}
