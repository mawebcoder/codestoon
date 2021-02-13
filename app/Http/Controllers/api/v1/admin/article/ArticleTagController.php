<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\Http\Requests\articles\tag\DeleteMultipleValidation;
use App\Http\Requests\articles\tag\StoreValidation;
use App\Http\Requests\articles\tag\UpdateValidation;
use App\models\ArticleTag;

class ArticleTagController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $error_message = ['message' => 'failed', 'data' => null];

    public function __construct()
    {
        //TODO SET PERMISSIONS IN THE SYSTEM HERE WITH MIDDLE WARES
    }


    public function edit(ArticleTag $articleTag)
    {
        return response(['message' => 'success', 'data' => $articleTag]);
    }

    public function getActiveTags()
    {
        if (!request()->has('search')) {

            if (request()->has('select_box')) {
                $article_tags = ArticleTag::select('id', 'fa_title', 'updated_at', 'created_at', 'en_title', 'status')
                    ->whereStatus(1)->get();
            }
            else{
                $article_tags = ArticleTag::select('id', 'fa_title', 'updated_at', 'created_at', 'en_title', 'status')
                    ->whereStatus(1)->paginate(30);
            }
            return $article_tags->isNotEmpty() ?
                response(['message' => 'success', 'data' => $article_tags]) :
                response($this->empty_success_message, 204);
        }
        $article_tags = ArticleTag::where(function ($q) {
            $q->where('fa_title', 'like', '%' . request()->search . '%');
            $q->orWhere('en_title', 'like', '%' . request()->search . '%');
        })->where('status', 1)->select('id', 'fa_title', 'en_title', 'updated_at', 'created_at', 'status')
            ->paginate(30);
        return $article_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $article_tags]) :
            response($this->empty_success_message, 204);

    }

    public function getDeActiveTags()
    {
        if (!request()->has('search')) {
            $article_tags = ArticleTag::select('id', 'fa_title', 'updated_at', 'created_at', 'en_title', 'status')
                ->whereStatus(0)->paginate(30);
            return $article_tags->isNotEmpty() ?
                response(['message' => 'success', 'data' => $article_tags]) :
                response($this->empty_success_message, 204);
        }
        $article_tags = ArticleTag::where(function ($q) {
            $q->where('fa_title', 'like', '%' . request()->search . '%');
            $q->orWhere('en_title', 'like', '%' . request()->search . '%');

        })->where('status', 0)->select('id', 'fa_title', 'en_title', 'updated_at', 'created_at', 'status')
            ->paginate(30);
        return $article_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $article_tags]) :
            response($this->empty_success_message, 204);
    }

    public function index()
    {
        if (!request()->has('search')) {
            $article_tags = ArticleTag::select('id', 'fa_title', 'en_title', 'updated_at', 'created_at', 'status')
                ->paginate(2);
            return $article_tags->isNotEmpty() ?
                response(['message' => 'success', 'data' => $article_tags]) :
                response($this->empty_success_message, 204);
        }

        $article_tags = ArticleTag::where('fa_title', 'like', '%' . request()->search . '%')
            ->orWhere('en_title', 'like', '%' . request()->search . '%')
            ->select('id', 'fa_title', 'en_title', 'updated_at', 'created_at', 'status')
            ->paginate(30);
        return $article_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $article_tags]) :
            response($this->empty_success_message, 204);

    }


    public function store(StoreValidation $storeValidation)
    {
        $data = $storeValidation->only(['fa_title', 'en_title']);

        $data['status'] = $storeValidation->status ? 1 : 0;

        $result = ArticleTag::create($data);

        return $result ?
            response($this->empty_success_message, 201) :
            response($this->error_message, 500);
    }

    public function update(ArticleTag $articleTag, UpdateValidation $updateValidation)
    {
        $data = $updateValidation->only(['fa_title', 'en_title']);

        $data['status'] = $updateValidation->status ? 1 : 0;

        $result = $articleTag->update($data);

        return $result ?
            response($this->empty_success_message) :
            response($this->error_message);
    }

    public function destroy(ArticleTag $articleTag)
    {
        $result = $articleTag->delete();
        return $result ?
            response($this->empty_success_message) :
            response($this->error_message);
    }

    public function deleteMultipleArticleTag(DeleteMultipleValidation $deleteMultipleValidation)
    {
        ArticleTag::whereIn('id', $deleteMultipleValidation->ids)->delete();
        return response($this->empty_success_message);
    }


    public function forceDelete(DeleteMultipleValidation $deleteMultipleValidation)
    {
        $result = ArticleTag::onlyTrashed()->whereIn('id', $deleteMultipleValidation->ids)
            ->forceDelete();
        return response($this->empty_success_message);
    }


    public function restore(DeleteMultipleValidation $deleteMultipleValidation)
    {
        $ids = $deleteMultipleValidation->ids;
        ArticleTag::withTrashed()->whereIn('id', $ids)->restore();
        return response($this->empty_success_message);
    }


    public function getTrashed()
    {
        if (!request()->has('search')) {
            $all_trashed_article_tags = ArticleTag::onlyTrashed()
                ->select('fa_title', 'en_title', 'id')
                ->paginate(30);

            return $all_trashed_article_tags->isNotEmpty() ?
                response(['message' => 'success', 'data' => $all_trashed_article_tags]) :
                response($this->error_message, 204);
        }
        $all_trashed_article_tags = ArticleTag::onlyTrashed()
            ->select('fa_title', 'en_title', 'id')
            ->where('fa_title', 'like', '%' . request()->search . '%')
            ->orWhere('en_title', 'like', '%' . request()->search . '%')
            ->paginate(30);

        return $all_trashed_article_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $all_trashed_article_tags]) :
            response($this->error_message, 204);


    }

}
