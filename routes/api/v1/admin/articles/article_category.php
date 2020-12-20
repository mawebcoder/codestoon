<?php

use Illuminate\Support\Facades\Route;


Route::resource('articles', 'ArticleController');
Route::resource('articles/categories', 'ArticleCategoryController');
