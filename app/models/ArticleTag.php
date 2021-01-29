<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleTag extends Model
{
    protected $guarded = ['id'];
    use SoftDeletes;

    /**
     * make many  to many relationship between article_tags table and the articles table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_tag', 'articleTag_id', 'article_id');
    }

}
