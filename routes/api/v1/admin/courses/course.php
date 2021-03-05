<?php

use Illuminate\Support\Facades\Route;



//course categories
Route::get('/courses/categories', 'CourseCategoryController@index');
Route::get('/courses/categories/actives', 'CourseCategoryController@getActiveCourseCategory')->name('course.category.actives');
Route::get('/courses/categories/de-actives', 'CourseCategoryController@getDeActiveCourseCategory')->name('course.category.actives');
Route::post('/courses/categories', 'CourseCategoryController@store')->name('courses.category.store');
Route::post('/courses/categories/{courseCategory}', 'CourseCategoryController@update')->name('courses.category.update');
Route::delete('/courses/categories/{courseCategory}', 'CourseCategoryController@destroy')->name('courses.category.delete');
Route::post('/courses/category/force-delete', 'CourseCategoryController@forceDelete')->name('course.category.force.delete');
Route::get('/courses/category/trashed', 'CourseCategoryController@getTrashed')->name('course.category.trashed');
Route::get('/courses/categories/edit/{courseCategory}', 'CourseCategoryController@edit')->name('course.categories.edit');
Route::post('/courses/category/restore', 'CourseCategoryController@restore')->name('course.category.restore');
Route::post('/courses/delete/categories/delete-multi', 'CourseCategoryController@deleteMulti')->name('course.category.delete.multi');







//course tags
Route::get('/courses/tags/actives','CourseTagController@getActivesCourseTags')->name('course.tags.actives');
Route::get('/courses/tags/de-actives','CourseTagController@getDeActiveCourseTags')->name('course.tags.de-actives');
Route::post('/courses/tags', 'CourseTagController@store')->name('course.tag.store');
Route::put('/courses/tags/{courseTag}', 'CourseTagController@update')->name('course.tag.update');
Route::delete('/courses/tags/{courseTag}', 'CourseTagController@destroy')->name('course.tag.destroy');
Route::get('/courses/tags', 'CourseTagController@index')->name('course.tag.index');
Route::get('/courses/tags/{courseTag}/edit', 'CourseTagController@edit')->name('course.tag.edit');
Route::post('/courses/tags/delete-multiple', 'CourseTagController@deleteMultiple')->name('course.tag.delete.multi');









//Course sections
Route::get('/courses/sections/actives','CourseSectionController@getActiveCourseSection')->name('course.sections.actives');
Route::get('/courses/sections/de-actives','CourseSectionController@getDeActiveCourseSection')->name('course.sections.de-actives');
Route::get('/courses/sections', 'CourseSectionController@index')->name('course.sections');
Route::get('/courses/sections/{courseSection}/edit', 'CourseSectionController@edit')->name('course.section.edit');
Route::get('/courses/sections/trashed', 'CourseSectionController@getTrashed')->name('course.sections.trashed');
Route::post('/courses/sections/delete-multi', 'CourseSectionController@deleteMulti')->name('course.section.multi');
Route::post('/courses/sections/restore', 'CourseSectionController@restore')->name('course.sections.restore');
Route::post('/courses/sections/force-delete', 'CourseSectionController@forceDelete')->name('course.section.force-delete');
Route::post('/courses/sections', 'CourseSectionController@store')->name('course.section.store');
Route::put('/courses/sections/{courseSection}', 'CourseSectionController@update')->name('course.section.update');
Route::delete('/courses/sections/{courseSection}', 'CourseSectionController@destroy')->name('course.section.destroy');
Route::get('/courses/sections', 'CourseSectionController@index')->name('course.section.index');










//Courses
Route::post('/courses/force-delete', 'CourseController@forceDelete')->name('course.force-delete');
Route::get('/courses/actives', 'CourseController@getActiveCourses')->name('courses.actives');
Route::get('/courses/de-actives', 'CourseController@getDeActiveCourses')->name('courses.de-actives');
Route::post('/courses/restore', 'CourseController@restore')->name('courses.restore');
Route::get('/courses/trashed', 'CourseController@getTrashed')->name('course.trashed');
Route::post('/courses/delete-multiple', 'CourseController@deleteMultiple')->name('course.delete.multi');
Route::post('/courses/switch-status/{course}','CourseController@switchCondition')->name('course-switch-condition');
Route::post('/courses/update/{course}','CourseController@update')->name('course-update');
Route::resource('courses', 'CourseController')->except(['show','update']);
