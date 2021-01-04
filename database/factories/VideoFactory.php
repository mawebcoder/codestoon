<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Video;
use Faker\Generator as Faker;

$factory->define(Video::class, function (Faker $faker) {
    return [
        'fa_title'=>$faker->title,
        'en_title'=>$faker->title,
        'time'=>$faker->time(),
        'is_free'=>1,
        'description'=>$faker->text,
        'is_single_video'=>0,
        'is_special_subscription'=>0,
        'courseSection_id'=>factory(\App\models\CourseSection::class)->create()->id,
        'course_id'=>factory(\App\models\Course::class)->create()->id,
        'short_description'=>$faker->text,
        'meta'=>$faker->title,
        'video_url_name'=>$faker->url,
    ];
});
