<?php

namespace App\Http\Controllers\api\v1\admin\video;

use App\Http\Controllers\Controller;
use App\models\Course;
use App\models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $failed_message = ['message' => 'success', 'data' => null];

    public function __construct()
    {
        //TODO SET THE PERMISSIONS
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $videos = Course::where('is_active', 1)->select('id', 'fa_title', 'user_id')->with([
            'videos:id,course_id,fa_title,status,time',
            'teacher:id,name,family'
        ])
            ->get();
        $single_videos = Video::where('is_single_video', 1)->select('id', 'fa_title,status,time')
            ->get();
    }

    public function getActiveVideos()
    {
        $active_videos = Course::where('is_active', 1)->select('id', 'fa_title', 'user_id')->with([
            'videos:id,course_id,fa_title,status,time',
            'teacher:id,name,family'
        ])->whereHas('videos',function ($video){
            $video->whereStatus(1);
        })->get();
        return $active_videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $active_videos]) :
            response($this->empty_success_message);
    }

    public function getDeActiveVideos()
    {
        $active_videos = Course::where('is_active', 1)->select('id', 'fa_title', 'user_id')->with([
            'videos:id,course_id,fa_title,status,time',
            'teacher:id,name,family'
        ])->whereHas('videos',function ($video){
            $video->whereStatus(0);
        })->get();
        return $active_videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $active_videos]) :
            response($this->empty_success_message);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //TODO VALIDATION OF THE STORE VIDEO
    public function store(Request $request)
    {


        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'short_description',
            'hour',
            'min',
            'sec',
            'is_free',
            'is_single_video',
            'is_special_subscription',
            'courseSection_id',
            'course_id',
            'meta',
            'video_url_name'
        ]);
        $data['time'] = $data['hour'] . ':' . $data['min'] . ':' . $data['sec'];
        $video = Video::create([
            'meta' => $data['meta'],
            'status' => $request->status ? 1 : 0,
            'is_special_subscription' => $data['is_special_subscription'] ?? 0,
            'short_description' => $data['short_description'],
            'is_free' => $data['is_free'] ?? 0,
            'en_title' => $data['en_title'],
            'fa_title' => $data['fa_title'],
            'courseSection_id' => $data['courseSection_id'] ?? null,
            'course_id' => $data['course_id'] ?? null,
            'description' => $data['description'],
            'is_single_video' => $data['is_single_video'] ?? 0,
            'time' => $data['time']
        ]);

        $video->tags()->sync($request->video_tag_ids);

        return $video ?
            response($this->empty_success_message, 201) :
            response($this->failed_message);

    }

    //TODO VALIDATION OF THE UPLOAD VIDEO
    public function upload(Request $request, Video $video)
    {
        ini_set('max_execution_time', 0);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = $video->is_single_video ?
                $path = 'unique_videos/' . $video->id :
                $path = 'courses/' . $video->course_id . '/' . $video->id;

            //has uploaded the video before?
            if ($video->video_url_name) {
                //is a single video?
                if ($video->is_single_video) {
                    if (Storage::disk('videos')->exists($path)) {

                        Storage::deleteDirectory($path);
                    }
                    //is not a single video?
                } else {
                    if (Storage::disk('videos')->exists($path)) {
                        Storage::deleteDirectory($path);
                    }
                }
            }
            $file->storeAs($path, $file_name, 'videos');
            $video->update([
                'video_url_name' => $file_name
            ]);
            return response($this->empty_success_message, 201);
        }
        return response($this->empty_success_message, 200);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $video = $video->with('course')->first();

        return response(['message' => 'success', 'data' => $video]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Video $video
     * @return \Illuminate\Http\JsonResponse
     */
    //TODO VALIDATION OF THE UPDATE VIDEO
    public function update(Request $request, Video $video)
    {

        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'short_description',
            'video_tags',
            'min',
            'sec',
            'hour',
            'is_free',
            'description',
            'is_single_video',
            'is_special_subscription',
            'courseSection_id',
            'course_id',
            'short_description',
            'meta',
            'video_tags'
        ]);
        $data['time'] = $data['hour'] . ':' . $data['min'] . ':' . $data['sec'];
        $video->update(Arr::except($data, ['hour', 'min', 'sec', 'video_tags']));
        $video->tags()->sync($data['video_tags']);
        return response()->json($this->empty_success_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
       $result=$video->delete();
       return  response($this->empty_success_message);
    }

    //TODO VALIDATION OF  THE RESTORE VIDEO IN THE SYSTEM
    public function restore()
    {
        $result=Video::onlyTrashed()->whereIn('id',request()->ids)
            ->restore();

        return response($this->empty_success_message);
    }

    //TODO VALIDATION OF VIDEO FORCE DELETE
    public function forceDelete()
    {
        //TODO FORCE DELETE VIDEO
    }

    public function getTrashed()
    {
        //TODO GET ALL VIDEO TRASHED
    }

    //TODO VALIDATION OF THE DELETE MULTIPLE VIDEO
    public function deleteMultiple()
    {
        Video::whereIn('id',request()->ids)
        ->delete();

        return response($this->empty_success_message);
    }
}
