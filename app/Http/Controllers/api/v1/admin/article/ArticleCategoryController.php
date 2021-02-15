<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\Http\Requests\articles\category\DeleteMultiple;
use App\Http\Requests\articles\category\StoreArticle;
use App\Http\Requests\articles\category\UpdateArticleValidation;
use App\models\ArticleCategory;
use http\Env\Request;
use Illuminate\Support\Facades\Storage;

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
        //TODO SET PERMISSIONS IN THE SYSTEM HERE WITH MIDDLE WARES
    }


    public function index()
    {

        $article_categories = ArticleCategory::select(
            'id',
            'fa_title',
            'status',
            'created_at',
            'parent',
            'en_title',
            'description',
            'updated_at')->with('father:id,fa_title');
        if (!request()->has('search')) {
            $article_categories = $article_categories->paginate(30);
        } else {
            $article_categories = $article_categories->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhere('en_title', 'like', '%' . request()->search . '%')
                ->get();
        }

        return $article_categories->isNotEmpty() ?
            response(['message' => 'success', 'data' => $article_categories]) :
            response($this->empty_success_message, 204);
    }


    public function getActiveCategories()
    {

        if (!request()->has('search')) {
            $article_categories = ArticleCategory::select(
                'id',
                'fa_title',
                'status',
                'created_at',
                'parent',
                'en_title',
                'description',
                'updated_at')->with('father:id,fa_title');

            if (request()->has('select_box')) {
                $article_categories = $article_categories->get();
            } else {
                $article_categories = $article_categories->whereStatus(1)->paginate(30);
            }
        } else {
            $article_categories = ArticleCategory::select(
                'id',
                'fa_title',
                'status',
                'created_at',
                'parent',
                'en_title',
                'description',
                'updated_at')->with('father:id,fa_title')
                ->whereStatus(1)
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->where('status', 1)
                ->get();
        }
        return $article_categories->isNotEmpty() ?
            response(['message' => 'success', 'data' => $article_categories]) :
            response($this->empty_success_message, 204);
    }

    public function edit(ArticleCategory $articleCategory)
    {
        $article_categories = ArticleCategory::whereStatus(1)->whereNotIn('id', [$articleCategory->id])->get();
        return response(
            [
                'message' => 'success',
                'data' => [
                    'category' => $articleCategory,
                    'categories' => $article_categories
                ],
            ]);
    }

    public function getDeActiveCategories()
    {
        if (!request()->has('search')) {
            $article_categories = ArticleCategory::select(
                'id',
                'fa_title',
                'status',
                'created_at',
                'parent',
                'en_title',
                'description',
                'updated_at')->with('father:id,fa_title')
                ->whereStatus(0);
            if (request()->has('select_box')) {
                $article_categories = $article_categories->get();
            } else {
                $article_categories = $article_categories->paginate(30);
            }
        } else {
            $article_categories = ArticleCategory::select(
                'id',
                'fa_title',
                'status',
                'created_at',
                'parent',
                'en_title',
                'description',
                'updated_at')->with('father:id,fa_title')
                ->whereStatus(0)
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->where('status', 0)
                ->get();
        }
        return $article_categories->isNotEmpty() ?
            response(['message' => 'success', 'data' => $article_categories]) :
            response($this->empty_success_message, 204);
    }


    public function show(ArticleCategory $articleCategory)
    {
        return response([
            'message' => 'success',
            'data' => [
                'article_category' => $articleCategory,
            ]
        ]);
    }

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
        $data['status'] = $storeArticle->status ? 1 : 0;

        $articleCategory = ArticleCategory::create($data);

        //article category cover upload

        $this->upload($articleCategory, $storeArticle->file('file'), $storeArticle);


        return $articleCategory ?
            response()->json($this->empty_success_message, 201) :
            response()->json($this->failed_message, 500);
    }

    public function upload($articleCategory, $file, $request)
    {
        if ($request->hasFile('file')) {
            $path = 'images/articles/categories/' . $articleCategory->id;
            $file_name = $file->getClientOriginalName();
            $full_path = $path . '/' . $file_name;

            if (is_dir($full_path)) {
                unlink($full_path);
            }

            $file->storeAs($path, $file_name, 'public');

            return $articleCategory->update([
                'cover_file_name' => $file_name
            ]);
        }
        return true;

    }

    public function destroy(ArticleCategory $articleCategory)
    {
        $result = $articleCategory->delete();
        return $result ? response()->json($this->empty_success_message, 200) :
            response()->json($this->failed_message, 500);
    }

    public function update(ArticleCategory $articleCategory, UpdateArticleValidation $updateArticleValidation)
    {

        $data = $updateArticleValidation->only(['fa_title', 'en_title', 'description']);

        $data['status'] = $updateArticleValidation->status ? 1 : 0;
        $data['parent'] = $updateArticleValidation->parent ?? 0;

        $result = $articleCategory->update($data);

        if ($updateArticleValidation->hasFile('file')) {
            $this->upload($articleCategory, $updateArticleValidation->file('file'), $updateArticleValidation);
        }

        return $result ? response()->json($this->empty_success_message) :
            response()->json($this->failed_message, 500);
    }

    public function deleteMultipleArticleCategory(DeleteMultiple $deleteMultiple)
    {
        $article_ids = $deleteMultiple->ids;
        ArticleCategory::whereIn('id', $article_ids)->delete();
        return response($this->empty_success_message, 200);
    }

    public function ForceDelete(DeleteMultiple $deleteMultiple)
    {
        $article_ids = $deleteMultiple->ids;

        $target = ArticleCategory::withTrashed()
            ->select('id', 'cover_file_name')
            ->whereIn('id', $article_ids);

//        get all covers of the these categories files
        $cover_file_names = $this->getAllFilesNames($target);

        $result = $target->forceDelete();

        //delete all covers from host
        $this->deleteFiles($cover_file_names);

        return response($this->empty_success_message, 200);
    }

    public function getAllFilesNames($target)
    {
        $cover_file_names = [];
        foreach ($target->get() as $item) {
            array_push($cover_file_names, ['cover_file_name' => $item->cover_file_name, 'id' => $item->id]);
        }

        return $cover_file_names;
    }

    public function deleteFiles($cover_file_names)
    {
        foreach ($cover_file_names as $item) {

            $file_path = storage_path('app/public/images/articles/categories/' . $item['id']);
            if (is_dir($file_path)) {
                Storage::disk('public')->deleteDirectory('images/articles/categories/' . $item['id']);
            }

        }
    }


    public function restore(DeleteMultiple $deleteMultiple)
    {
        $ids = $deleteMultiple->ids;
        $result = ArticleCategory::onlyTrashed()
            ->select('id')->whereIn('id', $ids)->restore();
        return response($this->empty_success_message);
    }
    public function mohammad(){

    }

    public function getTrashedArticleCategory()
    {
        if(!request()->has('search')){
            $trashed = ArticleCategory::onlyTrashed()->select
            (
                'id',
                'fa_title',
                'status',
                'created_at',
                'parent',
                'en_title',
                'description',
                'updated_at')->with('father:id,fa_title')->paginate(3);
        }else{
            $trashed = ArticleCategory::onlyTrashed()->select
            (
                'id',
                'fa_title',
                'status',
                'created_at',
                'parent',
                'en_title',
                'description',
                'updated_at')->with('father:id,fa_title')
                ->where('fa_title','like','%'.request()->search.'%')
                ->orWhere('en_title','like','%'.request()->search.'%')
                ->get();
        }
        return response()->json([
            'message' => 'success',
            'data' => $trashed
        ]);

    }


}
