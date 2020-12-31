<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'fa_title'=>$faker->fa_title,
        'en_title'=>$faker->title,
        'time'=>\Illuminate\Support\Str::random(20),
        'price'=>$faker->numberBetween(20,50),
        'lever'=>'beginner',
        'course_teacher'=>$faker->title,
        'course_image_cover'=>$faker->title,
        'description'=>$faker->text,
    ];
});
