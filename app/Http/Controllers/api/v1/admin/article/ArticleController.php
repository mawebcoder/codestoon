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


    /**
     * @OA\Post(
     * path="/articles",
     * summary="store new article",
     * description="store new article in the system",
     * tags={"articles"},
     * @OA\RequestBody(
     *    required=true,
     *    description="required parameters",
     *    @OA\JsonContent(
     *       required={"fa_title","en_title","meta","text","short_description"},
     *       @OA\Property(property="fa_title", type="string", format="title", example="article title"),
     *       @OA\Property(property="en_title", type="string", format="title", example="english article title"),
     *       @OA\Property(property="text", type="string", example="string"),
     *       @OA\Property(property="short_description", type="string", example="short_description"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="success response of the request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="failed", example="failed"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     * )
     */
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
            if ($request->article_tags->count()) {
                $article->tags()->sync($request->article_tags);
            }
            return $result ?
                response()->json($this->empty_success_message, 201) :
                response()->json($this->failed_message, 500);
        }
        return response()->json($this->failed_message, 500);
    }

    /**
     * @OA\delete(
     * path="/articles/{article}",
     * summary="delete article",
     * description="delete  article from the system",
     * tags={"articles"},
     * @OA\parameter(
     *          name="article",
     *           in="path",
     *           required=true,
     *           description="the article id",
     *           @OA\schema(
     * type="integer",
     *
     * ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="success response of the request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="failed", example="failed"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     * )
     */
    public function destroy(Article $article)
    {
        $result = $article->delete();
        return $result ?
            response()->json($this->empty_success_message) :
            response()->json($this->failed_message, 500);
    }


    /**
     * @OA\put(
     * path="/articles/{article}",
     * summary="update article",
     * description="update  article in the system",
     * tags={"articles"},
     * @OA\parameter(
     *          name="article",
     *           in="path",
     *           required=true,
     *           description="the article id",
     *           @OA\schema(
     * type="integer",
     * ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="success response of the request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="failed", example="failed"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     * )
     */
    public function update(Article $article)
    {
        $data = request()->only([
            'meta',
            'fa_title',
            'en_title',
            'short_description',
        ]);
        $data['content'] = request()->text;

        $update_result = $article->update($data);

        $sync_result = $article->articleCategories()->sync(request()->article_categories);

        if (request()->article_tags->count()) {
            $article->tags()->sync(request()->article_tags);
        }

        return $update_result && $sync_result ?
            response()->json($this->empty_success_message) :
            response()->json($this->failed_message);
    }

    /**
     * @OA\post(
     * path="/articles/delete/multi",
     * summary="delete some articles from database",
     * description="delete   articles in the system",
     * tags={"articles"},
     * @OA\RequestBody(
     *    required=true,
     *    description="pass articles ids",
     *    @OA\JsonContent(
     *       required={"ids"},
     *       @OA\Property(property="ids", type="object", format="array", example="{ids:[6,2,3,4]}"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success response of the request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="failed", example="failed"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     * )
     */
    public function deleteMultipleArticle()
    {
        $ids = request()->ids;
        $result = Article::find($ids)->delete();

        return $result ?
            response()->json($this->empty_success_message) :
            response()->json($this->failed_message);
    }

    /**
     * @OA\post(
     * path="/articles/force-delete",
     * summary="force delete some articles from database",
     * description="force delete   articles in the system",
     * tags={"articles"},
     * @OA\RequestBody(
     *    required=true,
     *    description="pass articles ids",
     *    @OA\JsonContent(
     *       required={"ids"},
     *       @OA\Property(property="ids", type="object", format="array", example="{ids:[6,2,3,4]}"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="success response of the request",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="failed", example="failed"),
     *       @OA\Property(property="data", type="boolean", example="null"),
     *        )
     *     ),
     * )
     */
    public function forceDeleteMultipleArticle()
    {

        $ids = request()->ids;
        $result = Article::withTrashed()->find($ids)->forceDelete();
        return $result ?
            response()->json($this->empty_success_message) :
            response()->json($this->failed_message);
    }

    public function getTrashedArticles()
    {
        $articles = Article::withTrashed()->select('fa_title', 'en_title', 'id')->paginate(20);
        return $articles->isNotEmpty() ?
            response()->json([
                'message' => 'success',
                'data' => $articles
            ]) :
            response()->json([
                'message' => 'success',
                'data' => null
            ], 204);
    }

}
