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

    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function tags()
    {
        return $this->belongsToMany(VideoTag::class,'video_tag','video_id','videoTag_id');
    }

}
