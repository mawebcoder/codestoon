<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Course;
use Faker\Generator as Faker;

$factory->define(Course::class, function (Faker $faker) {
    return [
        'fa_title'=>$faker->title,
        'en_title'=>$faker->title,
        'time'=>\Illuminate\Support\Str::random(20),
        'price'=>$faker->numberBetween(20,50),
        'level'=>'beginner',
        'meta'=>'meta',
        'discount_value'=>10,
        'is_special_subscription'=>0,
        'short_description'=>'short_description',
        'user_id'=>factory(\App\User::class)->create()->id,
        'description'=>$faker->text,
        'is_completed_course'=>0,
        'courseCategory_id'=>factory(\App\models\CourseCategory::class)->create()->id,
        'course_image_cover'=>'image.txt',
        'is_active'=>0
    ];
});
