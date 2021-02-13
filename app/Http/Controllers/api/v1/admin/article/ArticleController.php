<?php

namespace App\Http\Controllers\api\v1\admin\article;

use App\Http\Controllers\Controller;
use App\Http\Requests\articles\DeleteArticleValidation;
use App\Http\Requests\articles\StoreValidation;
use App\Http\Requests\articles\UpdateArticleValidation;
use App\models\Article;
use App\models\ArticleCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        if (!request()->has('search')) {
            $articles = Article::select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->with('category:id,fa_title')->paginate(30);
        } else {
            $articles = Article::select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id')
                ->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhereHas('category', function ($category) {
                    $category->where('fa_title', 'like', '%' . request()->search . '%');
                })->with('category:id,fa_title')->get();
        }

        return $articles->isNotEmpty() ?
            response(['message' => 'success', 'data' => $articles]) :
            response(['message' => 'success'], 204);
    }

    public function getActiveArticles()
    {
        if (!request()->has('search')) {
            $articles = Article::select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->with('category:id,fa_title')->whereStatus(1)
                ->paginate(30);
        } else {
            $articles = Article::select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->with('category:id,fa_title')->
            where(function ($q) {
                $q->where('fa_title', 'like', '%' . request()->search . '%');
                $q->OrWhereHas('category', function ($category) {
                    $category->where('fa_title', 'like', '%' . request()->search . '%');
                });

            })->where('status', 1)->get();
        }

        return $articles->isNotEmpty() ?
            response(['message' => 'success', 'data' => $articles]) :
            response($this->empty_success_message, 204);
    }

    public function getDeActiveArticles()
    {
        if (!request()->has('search')) {
            $articles = Article::select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->with('category:id,fa_title')->whereStatus(0)
                ->paginate(30);
        } else {
            $articles = Article::select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->with('category:id,fa_title')->
            where(function ($q) {
                $q->where('fa_title', 'like', '%' . request()->search . '%');
                $q->OrWhereHas('category', function ($category) {
                    $category->where('fa_title', 'like', '%' . request()->search . '%');
                });

            })->where('status', 0)->get();
        }

        return $articles->isNotEmpty() ?
            response(['message' => 'success', 'data' => $articles]) :
            response($this->empty_success_message, 204);
    }

    public function edit(Article $article)
    {
        return response([
            'message' => 'success',
            'data' => [
                'article' => $article,
                'article_tags' => $article->tags
            ]
        ]);
    }

    public function show(Article $article)
    {
        return response([
            'message' => 'success',
            'data' => [
                'article' => $article,
                'category' => $article->category,
                'tags' => $article->tags
            ]
        ]);
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
            $article->tags()->sync(json_decode($request->article_tags, true));
        }
        return true;
    }

    public function UploadArticleImages()
    {
        $file_name = Str::random(40);
        $file_extension = request()->file('file')->getClientOriginalExtension();
        request()->file('file')->
        storeAs('public/images/articles/content', $file_name . '.' . $file_extension);
        return response([
            'location' => asset('storage/images/articles/content/' . $file_name . '.' . $file_extension)
        ]);
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

    public function switchStatus(Article $article)
    {
        $data['status'] = request()->status ? 1 : 0;
        $article->update([
            'status' => $data['status']
        ]);
    }


    public function deleteMultipleArticle(DeleteArticleValidation $deleteArticleValidation)
    {
        $ids = request()->ids;

        $result = Article::whereIn('id', $ids)->delete();

        return response($this->empty_success_message);
    }


    public function forceDelete(DeleteArticleValidation $deleteArticleValidation)
    {
        $ids = $deleteArticleValidation->ids;

        $target = Article::withTrashed()->select('id', 'cover_file_name')->whereIn('id', $ids);

        //get all files names
        $cover_file_names = $this->getAllFilesNames($target);

        $target->forceDelete();
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
            $file_path = storage_path('app/public/images/articles/covers/' . $item['id']);
            if (is_dir($file_path)) {
                Storage::disk('public')->deleteDirectory('images/articles/covers/' . $item['id']);
            }
        }
    }

    public function restore(DeleteArticleValidation $DeleteArticleValidation)
    {
        $ids = $DeleteArticleValidation->ids;

        Article::withTrashed()->whereIn('id', $ids)->restore();

        return response($this->empty_success_message);
    }

    public function getTrashed()
    {
        if (!request()->has('search')) {
            $articles = Article::onlyTrashed()->select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->with('category:id,fa_title')
                ->paginate(30);
        } else {
            $articles = Article::onlyTrashed()->select(
                'fa_title',
                'cover_file_name',
                'id',
                'updated_at',
                'created_at',
                'status',
                'short_description',
                'articleCategory_id'
            )->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhereHas('category', function ($cat) {
                    $cat->where('fa_title', 'like', '%' . request()->search . '%');
                })->with('category:id,fa_title')
                ->get();
        }


        $data = ['message' => 'success', 'data' => $articles];

        return $articles->isNotEmpty() ?
            response($data) :
            response($this->failed_message, 204);
    }

}
