<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Videotag;
use Faker\Generator as Faker;

$factory->define(Videotag::class, function (Faker $faker) {
    return [
        'fa_title'=>$faker->title,
        'en_title'=>$faker->title,
        'status'=>0
    ];
});
