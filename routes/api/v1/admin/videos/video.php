<?php
use Illuminate\Support\Facades\Route;


//tags
Route::post('/videos/tags','VideoTagController@store')->name('video.tags.store');
Route::delete('/videos/tags/{videoTag}','VideoTagController@destroy')->name('video.tags.delete');
Route::put('/videos/tags/{videoTag}','VideoTagController@update')->name('video.tags.update');
Route::get('/videos/tags','VideoTagController@index')->name('video.tags.index');

//videos
Route::resource('videos','VideoController');
