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

    public function index()
    {
        //TODO SHOW ALL ARTICLE TAGS IN PAGINATION HERE
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



    //TODO VALIDATION OF THE FORCE DELETE OF THE MULTIPLE ARTICLE TAG HERE
    public function ForceDeleteMultipleArticleTags()
    {
        //TODO FORCE DELETE OF THE ARTICLE TAGS HRER
    }



    //TODO VALIDATION OF THE RESTORE ARTICLE TAGS HERE
    public function restoreArticleTag()
    {
        //TODO TEST CAN RESTORE MULTIPLE ARTICLE TAG
    }



    public function getTrashedArticleTag()
    {
        //TODO GET TRASHED ARTICLE TAG
    }
}
