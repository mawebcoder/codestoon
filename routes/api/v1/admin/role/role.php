<?php
use Illuminate\Support\Facades\Route;



Route::post('/roles','RoleController@store')->name('role-store');
Route::put('/roles/{role}','RoleController@update')->name('role-update');
Route::get('/roles/{role}/edit','RoleController@edit')->name('role-edit');
Route::post('/roles/multi/delete','RoleController@deleteMultiple')->name('role-delete-multi');
Route::get('/roles','RoleController@index')->name('role-index');
