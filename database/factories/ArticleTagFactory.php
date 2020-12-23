<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\ArticleTag;
use Faker\Generator as Faker;

$factory->define(ArticleTag::class, function (Faker $faker) {
    return [
        'fa_title' => $faker->title
    ];
});
