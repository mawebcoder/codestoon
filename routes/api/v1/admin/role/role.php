<?php
use Illuminate\Support\Facades\Route;



Route::post('/roles','RoleController@store')->name('role-store');
Route::put('/roles/{role}','RoleController@update')->name('role-update');
Route::post('/roles/multi/delete','RoleController@deleteMultiple')->name('role-delete-multi');
Route::post('/roles','RoleController@index')->name('role-index');
