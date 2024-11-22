<?php

namespace App\Models;

use App\Enum\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    public const string COLUMN_ORDER_ID = 'order_id';
    public const string COLUMN_AMOUNT = 'amount';
    public const string COLUMN_STATUS = 'status';

    protected function casts():array
    {
        return [
            self::COLUMN_STATUS => PaymentStatus::class,
            self::COLUMN_AMOUNT => 'integer'
        ];
    }
}
