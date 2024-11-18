<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{

    public function casts(): array
    {
        return [
            'active' => 'boolean',
        ];
    }
}
