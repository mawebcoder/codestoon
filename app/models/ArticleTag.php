<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ArticleTag extends Model
{
    protected $guarded=['id'];

    /**
     * make many  to many relationship between article_tags table and the articles table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function articles(){
        return $this->belongsToMany(Article::class,'article_tag','articleTag_id','article_id');
    }
}
