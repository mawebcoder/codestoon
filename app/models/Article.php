<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        return $this->belongsToMany(ArticleCategory::class, 'article_category', 'article_id', 'articleCategory_id');
    }

    /**
     * make slug for article
     *
     * @param $value
     * @return string
     */
    public function setFaTitleAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
        return $this->attributes['fa_title'] = $value;
    }

    public function User()
    {
        return $this->belongsTo(User::class);
    }
    public function tags(){
        return $this->belongsToMany(ArticleTag::class,'article_tag','article_id','articleTag_id');
    }
}
