<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends User
{
    use BaseModelTrait;
    use HasApiTokens;
    use Notifiable;

    protected $guarded = [];
    public const string COLUMN_NAME = 'name';
    public const string COLUMN_EMAIL = 'email';
    public const string COLUMN_PASSWORD = 'password';
}
