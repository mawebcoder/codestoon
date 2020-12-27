<?php

use Illuminate\Support\Facades\Route;


Route::get('/courses/categories', 'CourseCategoryController@index');
Route::post('/courses/categories', 'CourseCategoryController@store')->name('courses.category.store');
Route::put('/courses/categories/{courseCategory}', 'CourseCategoryController@update')->name('courses.category.update');
Route::delete('/courses/categories/{courseCategory}', 'CourseCategoryController@destroy')->name('courses.category.delete');
Route::post('/courses/categories','CourseCategoryController@forceDelete')->name('course.category.force.delete');
