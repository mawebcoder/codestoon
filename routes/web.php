<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/',function (){
    $video_ids_with_course_ids=collect([['course_id'=>2,'video_id'=>1],['course_id'=>4,'video_id'=>2]]);
    $video_ids_with_course_ids->each(function ($item) {

        $path = 'courses/' . $item['course_id'] . '/' . $item['video_id'];

        $base_path = Storage::disk('videos');

        if ($base_path->exists($path)){

            $base_path->deleteDirectory($path);
        }


    });
});
