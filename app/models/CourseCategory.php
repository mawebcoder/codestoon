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
        return $this->belongsToMany(Course::class, 'category_course', 'courseCategory_id', 'course_id');
    }

    public function courseses()
    {
        return $this->hasMany(Course::class, 'courseCategory_id');
    }

    public function scopeGetAllParents($q, $course_child_id)
    {
        $ids = [$course_child_id];
        $child_row = CourseCategory::find($course_child_id);
        $first_parent = CourseCategory::whereId($child_row->parent)->get();
        if ($first_parent->count()) {
            array_push($ids,$first_parent->first()->id);
            $parent_id=$first_parent->first()->parent;
            while (true) {
                $next_parent = CourseCategory::whereId($parent_id)->get();
                if ($next_parent->count()) {
                    $parent_id = $next_parent->first()->id;
                    array_push($ids, $parent_id);
                    $parent_id=$next_parent->first()->parent;
                }else{
                    break;
                }
            }
        }
        return $ids;
    }


}
