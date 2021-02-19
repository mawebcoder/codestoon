<?php
use Illuminate\Support\Facades\Route;



Route::post('/users','UserController@store')->name('admin-user-store');
Route::get('/users/admins/list','UserController@getAdminsList')->name('admin-admins-list');
Route::get('/users/list','UserController@getUsersList')->name('admin-user-list');
Route::get('/users/teachers/list','UserController@getTeachersList')->name('admin-teachers-list');
Route::get('/users/teachers/list/actives','UserController@getActiveTeachersList')->name('admin-teachers-list-active');
Route::get('/users/teachers/list/de-actives','UserController@getDeActiveTeachersList')->name('admin-teachers-list-de-active');
Route::get('/users/teachers/courses/{user}','UserController@getTeacherCourses')->name('admin-teacher-courses');
Route::post('/users/update/user/{user}','UserController@updateUser')->name('admin-users-update-user');
