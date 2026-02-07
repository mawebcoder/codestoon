<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Price extends Model
{
    use BaseModelTrait;
    use HasFactory;

    protected $guarded = [];
    public const string COLUMN_PRICE = 'price';
    public const string COLUMN_COMPARE_AT_PRICE = 'compare_at_price';
    public const string COLUMN_PRICEABLE_ID = 'priceable_id';
    public const string COLUMN_PRICEABLE_TYPE = 'priceable_type';

    public function casts(): array
    {
        return [
            self::COLUMN_PRICE => 'double:4',
            self::COLUMN_COMPARE_AT_PRICE => 'double:4',
        ];
    }

    public function priceable(): MorphTo
    {
        return $this->morphTo();
    }
}
