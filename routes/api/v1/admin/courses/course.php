<?php

use Illuminate\Support\Facades\Route;

//course categories
Route::get('/courses/categories', 'CourseCategoryController@index');
Route::get('/courses/categories/actives','CourseCategoryController@getActiveCourseCategory')->name('course.category.actives');
Route::get('/courses/categories/de-actives','CourseCategoryController@getDeActiveCourseCategory')->name('course.category.actives');
Route::post('/courses/categories', 'CourseCategoryController@store')->name('courses.category.store');
Route::put('/courses/categories/{courseCategory}', 'CourseCategoryController@update')->name('courses.category.update');
Route::delete('/courses/categories/{courseCategory}', 'CourseCategoryController@destroy')->name('courses.category.delete');
Route::post('/courses/categories/force-delete', 'CourseCategoryController@forceDelete')->name('course.category.force.delete');
Route::get('/courses/categories/trashed', 'CourseCategoryController@getTrashed')->name('course.category.trashed');
Route::get('/courses/categories/edit/{courseCategory}','CourseCategoryController@edit')->name('course.categories.edit');
Route::post('/courses/categories/restore','CourseCategoryController@restore')->name('course.category.restore');
Route::post('/courses/categories/delete-multi','CourseCategoryController@deleteMulti')->name('course.category.delete.multi');
//course tags
Route::post('/courses/tags', 'CourseTagController@store')->name('course.tag.store');
Route::put('/courses/tags/{courseTag}', 'CourseTagController@update')->name('course.tag.update');
Route::delete('/courses/tags/{courseTag}', 'CourseTagController@destroy')->name('course.tag.destroy');
Route::get('/courses/tags', 'CourseTagController@index')->name('course.tag.index');
Route::get('/courses/tags/{courseTag}/edit','CourseTagController@edit')->name('course.tag.edit');
Route::post('/courses/tags/delete-multiple','CourseTagController@deleteMultiple')->name('course.tag.delete.multi');

//Course sections
Route::post('/courses/sections/delete-multi','CourseSectionController@deleteMulti')->name('course.section.multi');
Route::post('/courses/sections','CourseSectionController@store')->name('course.section.store');
Route::put('/courses/sections/{courseSection}','CourseSectionController@update')->name('course.section.update');
Route::delete('/courses/sections/{courseSection}','CourseSectionController@destroy')->name('course.section.destroy');
Route::get('/courses/sections','CourseSectionController@index')->name('course.section.index');



//Courses
Route::post('/courses/force-delete','CourseController@forceDelete')->name('course.force-delete');
Route::get('/courses/actives','CourseController@getActiveCourses')->name('courses.actives');
Route::get('/courses/de-actives','CourseController@getDeActiveCourses')->name('courses.de-actives');
Route::post('/courses/restore','CourseController@restore')->name('courses.restore');
Route::get('/courses/trashed','CourseController@getTrashed')->name('course.trashed');
Route::post('/courses/delete-multiple','CourseController@deleteMultiple')->name('course.delete.multi');
Route::resource('courses', 'CourseController')->except(['show']);
