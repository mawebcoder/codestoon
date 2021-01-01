<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class CourseTag extends Model
{
    protected $guarded = ['id'];

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_tag', 'courseTag_id', 'course_id');
    }
}
