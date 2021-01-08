<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\models\ArticleTag;

class ArticleTagController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $error_message = ['message' => 'failed', 'data' => null];

    public function index()
    {
        //TODO SHOW ALL ARTICLE TAGS IN PAGINATION HERE
    }

    //TODO STORE VALIDATION OF THE  ARTICLE TAG
    public function store()
    {
        $data = request()->only(['fa_title']);
        $result = ArticleTag::create($data);
        return $result ?
            response($this->empty_success_message, 201) :
            response($this->error_message, 500);
    }

    //TODO UPDATE VALIDATION OF THE UPDATE ARTICLE TAG
    public function update(ArticleTag $articleTag)
    {
        $data = request()->only(['fa_title']);
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

    //TODO VALIDATION OF THE DELETE MULTIPLE ARTICLE TAG
    public function deleteMultipleArticleTag()
    {
        //TODO DELETE ARTICLE MULTIPLE TAG
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
