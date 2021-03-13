<?php
use Illuminate\Support\Facades\Route;



Route::get('/comments/actives','CommentController@getActiveComments')->name('comments-actives');
Route::get('/comments/de-actives','CommentController@getDeActiveComments')->name('comments-de-actives');
Route::get('/comments/trashed','CommentController@getTrashed')->name('comments.trashed');
Route::post('/comments/restore','CommentController@restore')->name('comment.restore');
Route::post('/comments/force-delete','CommentController@forceDelete')->name('comments.force-delete');
Route::post('/comments/delete/multi','CommentController@deleteMulti')->name('comments.delete.multi');
Route::resource('comments','CommentController')->except(['index']);
