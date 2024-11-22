<?php

namespace App\Enum;

enum OrderStatusEnum: int
{
    case PENDING = 1;
    case PAID = 2;
    case CANCELED = 3;
}
