<?php

namespace App\models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
