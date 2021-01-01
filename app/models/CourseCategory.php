<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseCategory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     * make many to many relationship between courses table and the course_categories table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class,'category_course','courseCategory_id','course_id');
    }
    public function courseses(){
        return $this->hasMany(Course::class,'courseCategory_id');
    }


}
