<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\TeacherInformation;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(TeacherInformation::class, function (Faker $faker) {
    return [
        'teacher_id' => factory(\App\User::class)->create()->id,
        'address' => Str::random(20),
        'description' => Str::random(40),
        'nationality_code' => Str::random(10),
        'resume' => Str::random(15),
        'nationality_card_front' => Str::random(20),
        'nationality_card_back' => Str::random(30),
        'status' => 'active'
    ];
});
