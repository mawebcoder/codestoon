<?php

namespace App\Http\Controllers\api\v1\admin\video;

use App\Http\Controllers\Controller;
use App\models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use function PHPUnit\Framework\fileExists;

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

        $video->tags()->sync($request->video_tag_ids);

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

        if (!$video->is_single_video) {
            if ($video->video_url_name != null) {
                if (fileExists(storage_path('app/videos/courses/' . $video->course->id . '/' . $video->id . '/' . $video->video_url_name))) {
                    unlink(storage_path('app/videos/courses/' . $video->course->id . '/' . $video->id . '/' . $video->video_url_name));
                }
            }
        } else {
            if ($video->video_url_name != null) {
                if (fileExists(storage_path('app/videos/unique_videos/' . $video->id . '/' . $video->video_url_name))) {
                    unlink(storage_path('app/videos/unique_videos/' . $video->id . '/' . $video->video_url_name));
                }
            }
        }

        $file->storeAs($path, $file_name);
        $video->update([
            'video_url_name' => $file_name
        ]);
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
     * @return \Illuminate\Http\JsonResponse
     */
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

        $video->delete();
        if ($video->is_single_video) {
            if (file_exists(storage_path('videos/unique_videos/' . $video->id . '/' . $video->video_url_name))) {
                unlink(storage_path('videos/unique_videos/' . $video->id . '/' . $video->video_url_name));
            }
        } else {

            $course_id = $video->course->id;
            if (file_exists(storage_path('videos/courses/' . $course_id . '/' . $video->id . '/' . $video->video_url_name))) {
                unlink(file_exists(storage_path('videos/courses/' . $course_id . '/' . $video->id . '/' . $video->video_url_name)));
            }
        }
        return response($this->empty_success_message);
    }
}
