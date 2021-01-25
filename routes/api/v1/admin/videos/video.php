<?php
use Illuminate\Support\Facades\Route;


//tags
Route::get('/videos/tags','VideoTagController@index')->name('video.tag.index');
Route::post('/videos/tags','VideoTagController@store')->name('video.tags.store');
Route::delete('/videos/tags/{videoTag}','VideoTagController@destroy')->name('video.tags.delete');
Route::put('/videos/tags/{videoTag}','VideoTagController@update')->name('video.tags.update');
Route::get('/videos/tags','VideoTagController@index')->name('video.tags.index');

//videos
Route::post('/videos/upload/{video}','VideoController@upload')->name('videos.upload');
Route::resource('videos','VideoController');
