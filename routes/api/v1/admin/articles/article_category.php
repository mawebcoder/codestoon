<?php

use Illuminate\Support\Facades\Route;


Route::resource('articles', 'ArticleController')->except(['create','edit']);
Route::resource('articles/articleCategories', 'ArticleCategoryController')->except(['create','edit','show']);
