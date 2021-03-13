<?php
use Illuminate\Support\Facades\Route;


//tags
Route::get('/videos/tags','VideoTagController@index')->name('video.tag.index');
Route::post('/videos/tags','VideoTagController@store')->name('video.tags.store');
Route::put('/videos/tags/{videoTag}','VideoTagController@update')->name('video.tags.update');
Route::post('/videos/tags/delete-multi','VideoTagController@deleteMultiple')->name('video.tag.delete-multi');
Route::get('/videos/tags/actives','VideoTagController@getActiveVideoTags')->name('video-tag-actives');
Route::get('/videos/tags/de-actives','VideoTagController@getDeActiveVideoTags')->name('video-tag-actives');



//videos

Route::get('/videos/video/info/{video}','VideoController@getVideoInformation')->name('video-information');
Route::get('/video-stream/{video}','VideoController@streamVideo')->name('video-stream');
Route::get('/videos/trashed','VideoController@getTrashed')->name('video.trashes');
Route::post('/videos/force-delete','VideoController@forceDelete')->name('video.force-delete');
Route::post('/videos/delete/multi','VideoController@deleteMultiple')->name('videos.delete.multi');
Route::post('/videos/restore','VideoController@restore')->name('videos.restore');
Route::get('/videos/actives','VideoController@getActiveVideos')->name('video.actives');
Route::get('/videos/de-actives','VideoController@getDeActiveVideos')->name('video.actives');
Route::post('/videos/upload/{video}','VideoController@upload')->name('videos.upload');
Route::get('/videos/unuploaded','VideoController@getUnUploadedVideos')->name('videos-unuploaded');
Route::post('/videos/switch/condition/{video}','VideoController@switchCondition')->name('videos-switch-condition');
Route::get('/videos/single-videos','VideoController@getSingleVideos')->name('videos-single-video');
Route::resource('videos','VideoController');
