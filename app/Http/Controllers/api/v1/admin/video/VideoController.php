<?php

namespace App\Http\Controllers\api\v1\admin\video;

use App\Http\Controllers\Controller;
use App\models\Video;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $failed_message = ['message' => 'success', 'data' => null];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        return $video ?
            response($this->empty_success_message, 201) :
            response($this->failed_message);

    }

    public function upload(Request $request, Video $video)
    {
        ini_set('max_execution_time', 0);
        $file = $request->file('file');
        $file_name = $file->getClientOriginalName();
        $path = $video->is_single_video ?
            $path = 'videos/unique_videos/' . $video->id :
            $path = 'videos/courses/' . $video->course_id . '/' . $video->id;


        $file->storeAs($path, $file_name);
        return response($this->empty_success_message, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Video $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        //
    }
}
