<?php

namespace App\Enum;

enum PaymentStatus: int
{
    case APPROVED = 1;
    case CANCELED = 2;
    case PENDING = 3;
}
