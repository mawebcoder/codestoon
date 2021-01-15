<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'fa_title' => $faker->title,
        'en_title' => $faker->title,
        'meta' => $faker->text,
        'slug' => \Illuminate\Support\Str::slug(\Illuminate\Support\Str::random(30)),
        'short_description' => $faker->text,
        'content' => $faker->text,
        'status' => 0,
        'articleCategory_id' => factory(\App\models\ArticleCategory::class)->create()->id,
        'writer' => factory(\App\User::class)->create()->id,
        'Registrar' => factory(\App\User::class)->create()->id,
    ];
});
