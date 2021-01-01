<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CourseSection extends Model
{
    protected $guarded = ['id'];


    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
