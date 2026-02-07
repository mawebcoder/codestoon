<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TutorialStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tutorial extends Model implements HasMedia
{
    use BaseModelTrait;
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];

    public const string COLUMN_TITLE = 'title';
    public const string COLUMN_SLUG = 'slug';
    public const string COLUMN_CATEGORY_ID = 'category_id';
    public const string COLUMN_TIME = 'time';
    public const string COLUMN_STATUS = 'status';
    public const string COLUMN_DESCRIPTION = 'description';
    public const string COLUMN_SHORT_DESCRIPTION = 'short_description';
    public const string COLUMN_USER_ID = 'user_id';

    protected function casts(): array
    {
        return [
            self::COLUMN_STATUS => TutorialStatusEnum::class,
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class);
    }

    public function prices(): MorphMany
    {
        return $this->morphMany(Price::class, 'priceable');
    }

    public function price(): MorphOne
    {
        return $this->morphMany(Price::class, 'priceable')->one()->latestOfMany();
    }
}
