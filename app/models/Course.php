<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public function courseCategories()
    {
        return $this->belongsToMany(CourseCategory::class, 'category_course', 'course_id', 'courseCategory_id');
    }

    public function courseCategory()
    {
        return $this->belongsTo(CourseCategory::class,'courseCategory_id');
    }

    public function sections(){
        return $this->hasMany(CourseSection::class);
    }
    public function tags(){
        return $this->belongsToMany(CourseTag::class,'course_tag','course_id','courseTag_id');
    }
}
