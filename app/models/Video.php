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
        return $this->belongsTo(Course::class);
    }

    public function comments(){

        return $this->morphMany(Comment::class,'commentable','commentable_type','commentable_id');
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function tags()
    {
        return $this->belongsToMany(VideoTag::class, 'tag_video', 'video_id', 'videoTag_id');
    }

}
