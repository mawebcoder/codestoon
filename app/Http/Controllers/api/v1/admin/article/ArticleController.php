<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\Http\Requests\articles\DeleteArticleValidation;
use App\Http\Requests\articles\StoreValidation;
use App\Http\Requests\articles\UpdateArticleValidation;
use App\models\Article;
use App\models\ArticleCategory;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
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
        //TODO SET PERMISSIONS IN THE SYSTEM HERE WITH MIDDLEWARES
    }


    public function index()
    {
        //TODO SHOW ALL ARTICLES LIST HERE WITH PAGINATION
    }


    public function store(StoreValidation $request)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'short_description',
            'articleCategory_id',
            'meta',
            'writer'
        ]);

        $data['content'] = $request->text;

        $data['Registrar'] = Auth::id();

        $data['status'] = $request->status ? 1 : 0;

        $article = Article::create($data);

        $this->uploadCover($article, $request);

        $this->syncCategories($data, $article);

        $this->syncTags($article, $request);

        return response($this->empty_success_message, 201);

    }


    public function syncCategories($data, $article)
    {
        $all_parents_ids_of_category_and_itself_id = ArticleCategory::getAllParentsIds($data['articleCategory_id']);

        $article->articleCategories()->sync($all_parents_ids_of_category_and_itself_id);

        return true;
    }

    public function syncTags($article, $request)
    {
        if ($request->has('article_tags')) {
            $article->tags()->sync($request->article_tags);
        }
        return true;
    }

    public function uploadCover($article, $request)
    {
        if ($request->hasFile('file')) {

            $path = 'images/articles/covers/' . $article->id;
            $file_name = $request->file('file')->getClientOriginalName();

            if ($article->cover_file_name) {

                if (file_exists(storage_path('app/public/' . $path . '/' . $article->cover_file_name))) {
                    unlink(storage_path('app/public/' . $path . '/' . $article->cover_file_name));
                }
            }


            $request->file('file')->storeAs($path, $file_name, 'public');

            $article->update(['cover_file_name' => $file_name]);
        }
        return true;
    }


    public function destroy(Article $article)
    {
        $result = $article->delete();

        return response($this->empty_success_message);
    }


    public function update(Article $article, UpdateArticleValidation $request)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'short_description',
            'articleCategory_id',
            'meta',
            'writer'
        ]);

        $data['content'] = $request->text;

        $data['Registrar'] = Auth::id();

        $data['status'] = $request->status ? 1 : 0;

        $article->update($data);

        $this->uploadCover($article, $request);

        $this->syncCategories($data, $article);

        $this->syncTags($article, $request);

        return response($this->empty_success_message, 200);

    }


    public function deleteMultipleArticle(DeleteArticleValidation $deleteArticleValidation)
    {
        $ids = request()->ids;

        $result = Article::whereIn('id', $ids)->delete();

        return response($this->empty_success_message);
    }


    public function forceDelete(DeleteArticleValidation $deleteArticleValidation)
    {
        //TODO FORCE DELETE ARTICLES
    }


    public function restore(DeleteArticleValidation $DeleteArticleValidation)
    {
        $ids = $DeleteArticleValidation->ids;

        Article::withTrashed()->whereIn('id', $ids)->restore();

        return response($this->empty_success_message);
    }


    public function getTrashed()
    {
        $articles = Article::onlyTrashed()->select('fa_title', 'en_title', 'id')
            ->paginate(30);

        $data = ['message' => 'success', 'data' => $articles];

        return $articles->isNotEmpty() ?
            response($data) :
            response($this->failed_message, 204);
    }

}
