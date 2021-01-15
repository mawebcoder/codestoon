<?php

use Illuminate\Support\Facades\Route;

//article categories
Route::get('/articles/categories', 'ArticleCategoryController@index')->name('article.category.index');
Route::post('/articles/categories', 'ArticleCategoryController@store')->name('article.category.store');
Route::put('/articles/categories/{articleCategory}', 'ArticleCategoryController@update')->name('article.category.update');
Route::delete('/articles/categories/{articleCategory}', 'ArticleCategoryController@destroy')->name('article.category.destroy');
Route::post('/articles/categories/deleteMultipleArticleCategory', 'ArticleCategoryController@deleteMultipleArticleCategory')->name('delete.multiple.article.category');
Route::get('/articles/categories/trashed','ArticleCategoryController@getTrashedArticleCategory')->name('articles.categories.trashed');
Route::post('/articles/categories/force-delete','ArticleCategoryController@ForceDelete')->name('articles.categories.force.delete');
Route::post('/articles/categories/restore','ArticleCategoryController@restore')->name('articles.categories.restore');
Route::get('/articles/categories/actives','ArticleCategoryController@getActiveCategories')->name('articles.categories.actives');
Route::get('/articles/categories/de-actives','ArticleCategoryController@getDeActiveCategories')->name('articles.categories.deActives');
Route::get('/articles/categories/{articleCategory}','ArticleCategoryController@show')->name('articles.categories.show');
//articles tags
Route::post('/articles/tags', 'ArticleTagController@store')->name('article.tag.store');
Route::get('/articles/tags', 'ArticleTagController@index')->name('article.tag.index');
Route::put('/articles/tags/{articleTag}', 'ArticleTagController@update')->name('articles.tag.update');
Route::delete('/articles/tags/{articleTag}', 'ArticleTagController@destroy')->name('article.tag.destroy');
Route::post('/articles/tags/deleteMultipleArticleTags','ArticleTagController@deleteMultipleArticleTag')->name('article.tags.delete.multiple');
Route::post('/articles/tags/force-delete','ArticleTagController@forceDelete')->name('articles.tags.force.delete');
Route::get('/articles/tags/get-Trashed','ArticleTagController@getTrashed')->name('articles.tags.trashed');
Route::post('/articles/tags/restore','ArticleTagController@restore')->name('articles.tag.restore');
Route::get('/articles/tags/de-actives','ArticleTagController@getDeActiveTags')->name('articles.tags.deActives');
Route::get('/articles/tags/actives','ArticleTagController@getActiveTags')->name('articles.tags.actives');
//articles
Route::post('/articles/delete/multi', 'ArticleController@deleteMultipleArticle')->name('delete.article.multiple');
Route::get('/articles/trashed','ArticleController@getTrashed')->name('articles.trashed');
Route::post('/articles/force-delete', 'ArticleController@forceDelete')->name('article.forceDelete');
Route::post('/articles/restore','ArticleController@restore')->name('articles.restore');
Route::resource('articles', 'ArticleController')->except(['create', 'edit', 'show']);

