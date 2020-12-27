<?php

use Illuminate\Support\Facades\Route;


Route::get('/courses/categories', 'CourseCategoryController@index');
Route::post('/courses/categories', 'CourseCategoryController@store')->name('courses.store');

