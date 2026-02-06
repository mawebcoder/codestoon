<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Video extends Model implements HasMedia
{
    use BaseModelTrait;
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];
    public const string COLUMN_NAME = 'title';
    public const string COLUMN_SLUG = 'slug';
    public const string COLUMN_TIME_IN_HOURS = 'time_in_hours';
    public const string COLUMN_TIME_IN_MINUTES = 'time_in_minutes';
    public const string COLUMN_TIME_IN_SECONDS = 'time_in_seconds';
    public const string COLUMN_DESCRIPTION = 'description';
    public const string COLUMN_TUTORIAL_ID = 'tutorial_id';
    public const string COLUMN_ENABLED = 'enabled';

    protected function casts(): array
    {
        return [
            self::COLUMN_ENABLED => 'boolean',
        ];
    }

    public function tutorial(): BelongsTo
    {
        return $this->belongsTo(Tutorial::class);
    }
}
