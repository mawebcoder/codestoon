<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Blog extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = ['id'];


    protected function casts(): array
    {
        return [
            'published_at' => 'datetime'
        ];
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoryable', 'categoryables');
    }
}
