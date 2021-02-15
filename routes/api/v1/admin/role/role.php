<?php
use Illuminate\Support\Facades\Route;



Route::post('/roles','RoleController@store')->name('role-store');
