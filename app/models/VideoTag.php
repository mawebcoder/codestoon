<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    protected $guarded = ['id'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_tag', 'videoTag_id', 'video_id');
    }
}
