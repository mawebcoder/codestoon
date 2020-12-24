<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\models\ArticleTag;

class ArticleTagController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $error_message = ['message' => 'failed', 'data' => null];

    public function store()
    {
        $data = request()->only(['fa_title']);
        $result = ArticleTag::create($data);
        return $result ?
            response($this->empty_success_message,201) :
            response($this->error_message, 500);
    }
}
