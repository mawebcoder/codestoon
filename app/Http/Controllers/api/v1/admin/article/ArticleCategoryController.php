<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\models\ArticleCategory;

class ArticleCategoryController extends Controller
{
    public function index()
    {

    }

    /**
     * @OA\Post(
     * path="/api/articles/articlescateg{id}",
     * summary="Sign in",
     * description="Login by email, password",
     * tags={"auth"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="user1@mail.com"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *       @OA\Property(property="persistent", type="boolean", example="true"),
     *    ),
     * ),
     *     @OA\parameter(
     *          name="id",
     *           in="path",
     *           required=true,
     *           description="the user id",
     *              @OA\schema(
    type="integer",
     *
     * ),
     *     ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="users", type="object", example="{name:'mohammad',family:'amiri'}"),
     *       @OA\Property(property="status", type="integer", example="200"),
     *        )
     *     ),
     * )
     */
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
