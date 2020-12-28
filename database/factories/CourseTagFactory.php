<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\CourseTag;
use Faker\Generator as Faker;

$factory->define(CourseTag::class, function (Faker $faker) {
    return [
        'fa_title'=>$faker->title,
        'en_title'=>$faker->title
    ];
});
