<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseSection extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];


    public function course()
    {
        return $this->belongsTo(Course::class)->withTrashed();
    }

    public function videos()
    {
        return $this->hasMany(Video::class,'courseSection_id','id');
    }
}
