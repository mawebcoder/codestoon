<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function commentable()
    {
        return $this->morphTo();
    }
}
