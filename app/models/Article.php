<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Article extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    /**
     *  make many to many relations between articles table and article_categories table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articleCategories()
    {
        return $this->belongsToMany(ArticleCategory::class,'article_category','article_id','articleCategory_id');
    }
}
