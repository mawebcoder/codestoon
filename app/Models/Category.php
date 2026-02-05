<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use BaseModelTrait;
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];
    public const string COLUMN_NAME = 'name';
    public const string COLUMN_DESCRIPTION = 'description';
    public const string COLUMN_SLUG = 'slug';
    public const string COLUMN_ENABLED = 'enabled';
}
