<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\VideoTag;
use Faker\Generator as Faker;

$factory->define(VideoTag::class, function (Faker $faker) {
    return [
        'fa_title'=>$faker->title,
        'en_title'=>$faker->title,
        'status'=>0
    ];
});
