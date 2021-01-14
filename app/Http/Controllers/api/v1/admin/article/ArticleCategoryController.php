<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\Http\Requests\articles\category\DeleteMultiple;
use App\Http\Requests\articles\category\StoreArticle;
use App\Http\Requests\articles\category\UpdateArticleValidation;
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

    public function index()
    {
        //TODO SHOW ALL ARTICLE CATEGORIES LIST
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
        if ($storeArticle->hasFile('file')) {
            $this->upload($articleCategory, $storeArticle->file('file'));
        }

        return $articleCategory ?
            response()->json($this->empty_success_message, 201) :
            response()->json($this->failed_message, 500);
    }

    public function upload($articleCategory, $file)
    {
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
            $this->upload($articleCategory, $updateArticleValidation->file('file'));
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
            $file_path = storage_path('app/public/images/articles/categories/' . $item['id'] . '/' . $item['cover_file_name']);
            if (file_exists($file_path)) {
                unlink($file_path);
                rmdir(storage_path('app/public/images/articles/categories/' . $item['id']));
            }

        }
    }

    public function restore(DeleteMultiple $deleteMultiple)
    {
        $ids = $deleteMultiple->ids;
        $result = ArticleCategory::withTrashed()
            ->select('id')->whereIn('id', $ids)->restore();
        return response($this->empty_success_message);
    }

    public function getTrashedArticleCategory()
    {
        $trashed = ArticleCategory::onlyTrashed()->select('id', 'fa_title', 'en_title')->paginate(3);
        return response()->json([
            'message' => 'success',
            'data' => $trashed
        ]);

    }


}
