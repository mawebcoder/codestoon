<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\models\ArticleCategory;

class ArticleCategoryController extends Controller
{
    public function index()
    {

    }

    public function store()
    {
        $data = request()->only(['fa_title', 'en_title', 'description']);
        $result = ArticleCategory::create($data);
        if ($result) {
            $response_content = [
                'message' => 'success',
                'data' => null
            ];
            return response()->json($response_content, 201);
        }
        $response_content = [
            'message' => 'failed',
            'data' => null
        ];
        return response()->json($response_content, 500);
    }

    public function destroy(ArticleCategory $articleCategory)
    {

        $result = $articleCategory->delete();
        if ($result) {
            $response_content = [
                'message' => 'success',
                'data' => null
            ];
            return response()->json($response_content, 200);
        }
        $response_content = [
            'message' => 'failed',
            'data' => null
        ];
        return response()->json($response_content, 500);
    }

    public function update(ArticleCategory $articleCategory)
    {

    }

    public function show(ArticleCategory $articleCategory)
    {

    }

}
