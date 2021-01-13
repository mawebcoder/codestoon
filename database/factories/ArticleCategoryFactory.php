<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\ArticleCategory;
use Faker\Generator as Faker;

$factory->define(ArticleCategory::class, function (Faker $faker) {

    return [
        'fa_title' => $faker->title,
        'en_title' => $faker->title,
        'description' => $faker->text,
        'cover_file_name' => $faker->title,
        'parent' => 0
    ];
});
