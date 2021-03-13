<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Comment extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function getActiveComments($request)
    {

        if ($request->target === 'video') {
            $comments = Comment::query()
                ->where('commentable_type', "App\\models\\Video")
                ->whereActive(1)->paginate(30);

        } elseif ($request->target === 'course') {
            $comments = Comment::query()
                ->where('commentable_type', "App\\models\\Course")
                ->whereActive(1)->paginate(30);
        } elseif ($request->target === 'article') {

            $comments = Comment::query()
                ->where('commentable_type', "App\\models\\Article")
                ->whereActive(1)->paginate(30);

        } else {
           return 0;
        }
        return  $comments;
    }


    public function getDeActiveComments($request)
    {

        if ($request->target === 'video') {
            $comments = Comment::query()
                ->where('commentable_type', "App\\models\\Video")
                ->whereActive(0)->paginate(30);

        } elseif ($request->target === 'course') {
            $comments = Comment::query()
                ->where('commentable_type', "App\\models\\Course")
                ->whereActive(0)->paginate(30);
        } elseif ($request->target === 'article') {

            $comments = Comment::query()
                ->where('commentable_type', "App\\models\\Article")
                ->whereActive(0)->paginate(30);

        } else {
           return 0;
        }
        return  $comments;
    }


}
