<?php

/** @var Factory $factory */

use Illuminate\Database\Eloquent\Factory;
use Spatie\Permission\Models\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name'=>\Illuminate\Support\Str::random(20)
    ];
});
