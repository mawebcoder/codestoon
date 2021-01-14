<?php

use Illuminate\Support\Facades\Route;


Route::get('/articles/categories', 'ArticleCategoryController@index')->name('article.category.index');
Route::post('/articles/categories', 'ArticleCategoryController@store')->name('article.category.store');
Route::put('/articles/categories/{articleCategory}', 'ArticleCategoryController@update')->name('article.category.update');
Route::delete('/articles/categories/{articleCategory}', 'ArticleCategoryController@destroy')->name('article.category.destroy');
Route::post('/articles/categories/deleteMultipleArticleCategory', 'ArticleCategoryController@deleteMultipleArticleCategory')->name('delete.multiple.article.category');
Route::get('/articles/categories/trashed','ArticleCategoryController@getTrashedArticleCategory')->name('articles.categories.trashed');
Route::post('/articles/categories/force-delete','ArticleCategoryController@ForceDelete')->name('articles.categories.force.delete');
Route::post('/articles/categories/restore','ArticleCategoryController@restore')->name('articles.categories.force.delete');

//articles tags
Route::post('/articles/tags', 'ArticleTagController@store')->name('article.tag.store');
Route::put('/articles/tags/{articleTag}', 'ArticleTagController@update')->name('articles.tag.update');
Route::delete('/articles/tags/{articleTag}', 'ArticleTagController@destroy')->name('article.tag.destroy');



//articles
Route::post('/articles/delete/multi', 'ArticleController@deleteMultipleArticle')->name('delete.article.multiple');
Route::post('/articles/force-delete', 'ArticleController@forceDeleteMultipleArticle')->name('article.forceDelete');
Route::resource('articles', 'ArticleController')->except(['create', 'edit', 'show']);

