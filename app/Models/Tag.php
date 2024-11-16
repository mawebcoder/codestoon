<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    protected $guarded = ['id'];


    public function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }
}
