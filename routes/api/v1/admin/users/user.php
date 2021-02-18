<?php
use Illuminate\Support\Facades\Route;



Route::post('/users','UserController@store')->name('admin-user-store');
