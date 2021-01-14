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



}
