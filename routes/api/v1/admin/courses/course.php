<?php

use Illuminate\Support\Facades\Route;

//course categories
Route::get('/courses/categories', 'CourseCategoryController@index');
Route::post('/courses/categories', 'CourseCategoryController@store')->name('courses.category.store');
Route::put('/courses/categories/{courseCategory}', 'CourseCategoryController@update')->name('courses.category.update');
Route::delete('/courses/categories/{courseCategory}', 'CourseCategoryController@destroy')->name('courses.category.delete');
Route::post('/courses/categories/force-delete', 'CourseCategoryController@forceDelete')->name('course.category.force.delete');
Route::get('/courses/categories/trashed', 'CourseCategoryController@getTrashed')->name('course.category.trashed');

//course tags
Route::post('/courses/tags', 'CourseTagController@store')->name('course.tag.store');
Route::put('/courses/tags/{courseTag}', 'CourseTagController@update')->name('course.tag.update');
Route::delete('/courses/tags/{courseTag}', 'CourseTagController@destroy')->name('course.tag.destroy');
Route::get('/courses/tags', 'CourseTagController@index')->name('course.tag.index');

//Courses

Route::resource('courses', 'CourseController');
