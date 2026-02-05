<?php

declare(strict_types=1);

namespace App\Models;

trait BaseModelTrait
{
    public const string COLUMN_ID = 'id';

    public static function getTableName(): string
    {
        return new static()->getTable();
    }
}
