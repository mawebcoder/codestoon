<?php
use Illuminate\Support\Facades\Route;


//tags
Route::get('/videos/tags','VideoTagController@index')->name('video.tag.index');
Route::post('/videos/tags','VideoTagController@store')->name('video.tags.store');
Route::delete('/videos/tags/{videoTag}','VideoTagController@destroy')->name('video.tags.delete');
Route::put('/videos/tags/{videoTag}','VideoTagController@update')->name('video.tags.update');
Route::get('/videos/tags','VideoTagController@index')->name('video.tags.index');
Route::get('/videos/tags/{videoTag}/edit','VideoTagController@edit')->name('videos.tag.edit');
Route::post('/videos/tags/delete-multi','VideoTagController@deleteMultiple')->name('video.tag.delete-multi');

//videos
Route::get('/videos/actives','VideoController@getActiveVideos')->name('video.actives');
Route::get('/videos/de-actives','VideoController@getDeActiveVideos')->name('video.actives');
Route::post('/videos/upload/{video}','VideoController@upload')->name('videos.upload');
Route::resource('videos','VideoController');
