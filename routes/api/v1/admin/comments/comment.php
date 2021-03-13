<?php
use Illuminate\Support\Facades\Route;



Route::get('/comments/actives','CommentController@getActiveComments')->name('comments-actives');
Route::get('/comments/de-actives','CommentController@getDeActiveComments')->name('comments-de-actives');
Route::post('/comments/switch-status/{comment}','CommentController@switchCommentStatus')->name('comments-switch-status');
Route::post('/comments/delete/multi','CommentController@deleteMulti')->name('comments.delete.multi');
Route::resource('comments','CommentController')->except(['index','destroy']);
