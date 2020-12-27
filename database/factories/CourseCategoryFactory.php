<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\CourseCategory;
use Faker\Generator as Faker;

$factory->define(CourseCategory::class, function (Faker $faker) {
    return [
        'meta'=>$faker->title,
        'en_title'=>$faker->title,
        'fa_title'=>$faker->title,
        'description'=>$faker->text,
        'short_description'=>$faker->text,
    ];
});
