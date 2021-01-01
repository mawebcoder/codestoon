<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\CourseSection;
use Faker\Generator as Faker;

$factory->define(CourseSection::class, function (Faker $faker) {
    return [
        'fa_title' => $faker->title,
        'en_title' => $faker->title,
        'course_id' => factory(\App\models\Course::class)->create()->id
    ];
});
