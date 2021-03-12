<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function course()
    {
        return $this->belongsTo(Course::class)->withTrashed();
    }

    public function comments(){

        return $this->morphMany(Comment::class,'commentable')->withTrashed();
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class,'courseSection_id','id')->withTrashed();
    }

    public function tags()
    {
        return $this->belongsToMany(VideoTag::class, 'tag_video', 'video_id', 'videoTag_id')->withTrashed();
    }

}
