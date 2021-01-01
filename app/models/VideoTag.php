<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VideoTag extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function videos()
    {
        return $this->belongsToMany(Video::class, 'video_tag', 'videoTag_id', 'video_id');
    }
}
