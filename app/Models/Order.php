<?php

namespace App\Models;

use App\Enum\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{

    public const string COLUMN_USER_ID = 'user_id';
    public const string COLUMN_AMOUNT = 'amount';
    public const string COLUMN_SUBSCRIPTION_ID = 'subscription_id';
    public const string COLUMN_PAID_AT = 'paid_at';
    public const string COLUMN_STATUS = 'status';

    protected function casts(): array
    {
        return [
            self::COLUMN_AMOUNT => 'integer',
            self::COLUMN_STATUS => OrderStatusEnum::class,
            self::COLUMN_PAID_AT => 'datetime',
        ];
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
