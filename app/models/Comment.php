<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Comment extends Model
{

    protected $guarded = ['id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function getActiveComments($request)
    {

        if ($request->target === 'video') {
            $comments = Comment::query()->select('commentable_id','commentable_type','text','active','id')
                ->where('commentable_type', "App\\models\\Video")
                ->whereActive(1)->with(['user:id,email','video:id,fa_title'])->paginate(30);

        } elseif ($request->target === 'course') {
            $comments = Comment::query()->select('commentable_id','commentable_type','text','active','id')
                ->where('commentable_type', "App\\models\\Course")
                ->whereActive(1)->with(['user:id,email','course:id,fa_title'])->paginate(30);
        } elseif ($request->target === 'article') {

            $comments = Comment::query()->select('commentable_id','commentable_type','text','active','id')
                ->where('commentable_type', "App\\models\\Article")
                ->whereActive(1)->with(['user:id,email','article:id,fa_title'])->paginate(30);

        } else {
            return 0;
        }
        return $comments;
    }


    public function getDeActiveComments($request)
    {

        if ($request->target === 'video') {
            $comments = Comment::query()->select('commentable_id','commentable_type','text','active','id')
                ->where('commentable_type', "App\\models\\Video")
                ->whereActive(0)->with(['user:id,email','video:id,fa_title'])->paginate(30);

        } elseif ($request->target === 'course') {
            $comments = Comment::query()
                ->select('commentable_id','commentable_type','text','active','id')
                ->where('commentable_type', "App\\models\\Course")
                ->whereActive(0)->with(['user:id,email','course:id,fa_title'])->paginate(30);
        } elseif ($request->target === 'article') {

            $comments = Comment::query()->select('commentable_id','commentable_type','text','active','id')
                ->where('commentable_type', "App\\models\\Article")
                ->whereActive(0)->with(['user:id,email','article:id,fa_title'])->paginate(30);

        } else {
            return 0;
        }
        return $comments;
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function article()
    {
        return $this->belongsTo(Article::class,'commentable_id','id')->withTrashed();
    }

    public function course(){
        return $this->belongsTo(Course::class,'commentable_id','id')->withTrashed();
    }

    public function video(){

        return $this->belongsTo(Video::class,'commentable_id','id')->withTrashed();
    }


}
