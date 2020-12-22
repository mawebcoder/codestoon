<?php

use Illuminate\Support\Facades\Route;

Route::get('/articles/categories', 'ArticleCategoryController@index')->name('article.category.index');
Route::post('/articles/categories', 'ArticleCategoryController@store')->name('article.category.store');
Route::put('/articles/categories/{articleCategory}', 'ArticleCategoryController@update')->name('article.category.update');
Route::delete('/articles/categories/{articleCategory}', 'ArticleCategoryController@destroy')->name('article.category.destroy');
Route::resource('articles', 'ArticleController')->except(['create', 'edit','show']);
