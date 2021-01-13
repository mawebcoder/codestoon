<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\Http\Requests\articles\StoreArticle;
use App\models\ArticleCategory;

class ArticleCategoryController extends Controller
{

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


    /**
     * @OA\Get(
     *     path="/articles/categories",
     *     summary="articles categories list",
     *     description="get list of the articles categories",
     *     tags={"article category"},
     * @OA\Response(
     *    response=200,
     *    description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="data", type="collection", example="[{fa_title:'title',en_title:'title',description:'articlecategory description'},{fa_title:'title',en_title:'title',description:'articlecategory description'}]"),
     *       @OA\Property(property="message", type="strig", example="success"),
     *        )
     *     ),
     * )
     */
    public function index()
    {
        $article_categories_list = ArticleCategory::select('fa_title', 'en_title', 'id
        ')->paginate(20);
        return response()->json([
            'message' => 'success',
            'data' => $article_categories_list
        ]);
    }

    /**
     * @OA\Post(
     * path="/articles/categories",
     * summary="store new article category",
     * description="store new article category in databas",
     * tags={"article category"},
     * @OA\RequestBody(
     *    required=true,
     *    description="required",
     *    @OA\JsonContent(
     *       required={"fa_title","en_title","description"},
     *       @OA\Property(property="fa_title", type="string", format="article title", example="the article title in persian"),
     *       @OA\Property(property="en_title", type="string", format="password", example="the article title in english"),
     *       @OA\Property(property="description", type="string", example="article category description"),
     *    ),
     * ),
     * @OA\Response(
     *    response=201,
     *    description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="null", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="failed"),
     *       @OA\Property(property="data", type="null", example="null"),
     *        )
     *     ),
     * )
     * @param StoreArticle $storeArticle
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreArticle $storeArticle)
    {

        $data = $storeArticle->only(
            [
                'fa_title',
                'en_title',
                'description',
            ]
        );
        $data['parent'] = $storeArticle->parent ?? 0;
        $data['status'] = $storeArticle->status ?? 0;

        $articleCategory = ArticleCategory::create($data);

        //article category cover upload
        if ($storeArticle->file('file')) {
            $this->upload($articleCategory, $storeArticle->file('file'));
        }

        return $articleCategory ?
            response()->json($this->empty_success_message, 201) :
            response()->json($this->failed_message, 500);
    }

    /**
     * upload article category cover
     *
     * @param $articleCategory
     * @param $file
     * @return mixed
     */
    public function upload($articleCategory, $file)
    {
        $path = 'images/articles/categories/' . $articleCategory->id;
        $file_name = $file->getClientOriginalName();

        $file->storeAs($path, $file_name, 'public');

        return $articleCategory->update([
            'cover_file_name' => $file_name
        ]);
    }

    /**
     * @OA\delete(
     * path="/articles/categories/{articleId}",
     * summary="delete  article category",
     * description="delete  article category from databas",
     * tags={"article category"},
     * @OA\parameter(
     *          name="articleId",
     *           in="path",
     *           required=true,
     *           description="the articleId",
     *              @OA\schema(
    type="integer",
     * ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="null", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="failed"),
     *       @OA\Property(property="data", type="null", example="null"),
     *        )
     *     ),
     * )
     */

    public function destroy(ArticleCategory $articleCategory)
    {
        $result = $articleCategory->delete();
        return $result ? response()->json($this->empty_success_message, 200) :
            response()->json($this->failed_message, 500);
    }

    /**
     * @OA\put(
     * path="/articles/categories/{articleId}",
     * summary="update  article category",
     * description="update  article category from databas",
     * tags={"article category"},
     *@OA\RequestBody(
     *    required=true,
     *    description="required",
     *    @OA\JsonContent(
     *       required={"fa_title","en_title","description"},
     *       @OA\Property(property="fa_title", type="string", format="article title", example="the article title in persian"),
     *       @OA\Property(property="en_title", type="string", format="password", example="the article title in english"),
     *       @OA\Property(property="description", type="string", example="article category description"),
     *    ),
     * ),
     * @OA\parameter(
     *          name="articleId",
     *           in="path",
     *           required=true,
     *           description="the articleId",
     *              @OA\schema(
    type="integer",
     * ),
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="success",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="success"),
     *       @OA\Property(property="data", type="null", example="null"),
     *        )
     *     ),
     *@OA\Response(
     *    response=500,
     *    description="failed",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="failed"),
     *       @OA\Property(property="data", type="null", example="null"),
     *        )
     *     ),
     * )
     */

    //TODO UPDATE ARTICLE VALIDATION
    public function update(ArticleCategory $articleCategory)
    {
        $data = request()->only(['fa_title', 'en_title', 'description']);
        $result = $articleCategory->update($data);
        return $result ? response()->json($this->empty_success_message) :
            response()->json($this->failed_message, 500);
    }


    //TODO VALIDATION OF THE DELETE MULTIPLE ARTICLE CATEGORY
    public function deleteMultipleArticleCategory()
    {

        //TODO DELETE MULTIPLE ARTICLE CATEGORY  AND VALIDATION OF THIS
    }

    //TODO VALIDATION CAN RESTORE ARTICLE CATEGORIES
    public function restoreArticleCategory()
    {
        //TODO RESTORE MULTIPLE ARTICLE CATEGORY
    }

    public function getTrashedArticleCategory()
    {
        //TODO GET TRASHED ARTICLE CATEGORIES
    }


}
