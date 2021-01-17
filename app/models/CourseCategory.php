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

    public function ScopeGetAllParentsIds($q, $childId)
    {
        $all_parents_ids = [$childId];

        //check is there any parent for this record?
        $parent_id = CourseCategory::find($childId)->parent;

        while ($parent_id) {

            $parent = CourseCategory::find($parent_id);

            $all_parents_ids = array_merge($all_parents_ids, [$parent->id]);

            $parent_id = $parent->parent;
        }
        return $all_parents_ids;
    }

    public function father()
    {
        return $this->belongsTo(self::class, 'parent', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class,'parent','id');
    }


}
