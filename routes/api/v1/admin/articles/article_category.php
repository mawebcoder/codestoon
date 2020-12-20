<?php

use Illuminate\Support\Facades\Route;


Route::resource('articles', 'ArticleController')->except(['create','edit']);
Route::resource('articles/categories', 'ArticleCategoryController')->except(['create','edit']);
