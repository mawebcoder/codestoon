<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    /**
     *  make many to many relations between articles table and article_categories table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category', 'articleCategory_id', 'article_id');
    }

    public function ScopeGetAllParentsIds($q, $childId)
    {
        $all_parents_ids = [$childId];

        //check is there any parent for this record?
        $parent_id = ArticleCategory::find($childId)->parent;

        while ($parent_id) {

            $parent = ArticleCategory::find($parent_id);

            $all_parents_ids = array_merge($all_parents_ids, [$parent->id]);

            $parent_id = $parent->parent;
        }
        return $all_parents_ids;
    }

    public function manyArticles()
    {
        return $this->hasMany(Article::class,'articleCategory_id','id');
    }

}
