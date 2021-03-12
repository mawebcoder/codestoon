<?php

namespace App\Http\Controllers\api\v1\admin\video;

use App\Http\Controllers\Controller;
use App\Http\Requests\video\DeleteVideoValidation;
use App\Http\Requests\video\StoreVideoValidation;
use App\Http\Requests\video\tag\StoreVideoTagValidation;
use App\Http\Requests\video\UploadVideoValidation;
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

        if (!request()->has('search')) {
            $videos = Video::query()
                ->select(
                    'id',
                    'course_id',
                    'courseSection_id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->paginate(30);
        } else {
            $videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->select(
                    'id',
                    'course_id',
                    'courseSection_id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )
                ->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhereHas('course', function ($course) {
                    $course->where('fa_title', 'like', '%' . request()->search . '%');
                })->orWhereHas('section', function ($section) {
                    $section->where('fa_title', 'like', '%' . request()->search . '%');
                })->get();
        }

        return $videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $videos]) :
            response(['message' => 'success', 'data' => null], 204);

    }

    public function switchCondition(Video $video)
    {
        $status = $video->status ? 0 : 1;
        $video->update(['status' => $status]);
        return response(['message' => 'success', 'data' => null]);
    }

    public function getActiveVideos()
    {
        if (!request()->has('search')) {
            $videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->whereStatus(1)
                ->select(
                    'id',
                    'course_id',
                    'courseSection_id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )->paginate(30);
        } else {
            $videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->whereStatus(1)
                ->select(
                    'id',
                    'course_id',
                    'courseSection_id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');

                    $q->orWhereHas('course', function ($course) {
                        $course->where('fa_title', 'like', '%' . request()->search . '%');
                    });

                    $q->orWhereHas('section', function ($section) {
                        $section->where('fa_title', 'like', '%' . request()->search . '%');
                    });

                })->get();
        }

        return $videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $videos]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getDeActiveVideos()
    {
        if (!request()->has('search')) {
            $videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->whereStatus(0)
                ->select(
                    'id',
                    'course_id',
                    'courseSection_id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )->paginate(30);
        } else {
            $videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->whereStatus(0)
                ->select(
                    'id',
                    'course_id',
                    'courseSection_id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');

                    $q->orWhereHas('course', function ($course) {
                        $course->where('fa_title', 'like', '%' . request()->search . '%');
                    });

                    $q->orWhereHas('section', function ($section) {
                        $section->where('fa_title', 'like', '%' . request()->search . '%');
                    });

                })->get();
        }

        return $videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $videos]) :
            response(['message' => 'success', 'data' => null], 204);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreVideoValidation $request)
    {


        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'short_description',
            'is_free',
            'is_single_video',
            'is_special_subscription',
            'courseSection_id',
            'course_id',
            'meta',
            'video_url_name',
            'minute',
            'second'
        ]);

        $data['is_special_subscription'] = $data['is_special_subscription'] ?? 0;
        $data['time'] = $this->setVideoTime($data);

        $video = Video::create([
            'meta' => $data['meta'],
            'status' => $request->status ? 1 : 0,
            'is_special_subscription' => $data['is_special_subscription'] ? 1 : 0,
            'short_description' => $data['short_description'],
            'is_free' => $data['is_free'] ?? 0,
            'en_title' => $data['en_title'],
            'fa_title' => $data['fa_title'],
            'courseSection_id' => $data['courseSection_id'] ?? null,
            'course_id' => $data['course_id'] ?? null,
            'description' => $data['description'],
            'is_single_video' => $data['is_single_video'] ? 1 : 0,
            'time' => $data['time']
        ]);

        $video->tags()->sync($request->video_tag_ids);


        return $video ?
            response(['message' => 'success', 'data' => $video->id], 201) :
            response($this->failed_message);

    }

    public function setVideoTime($data)
    {
        if ($data['second']) {
            return $data['minute'] . ':' . $data['second'];
        }
        return $data['minute'];
    }


    public function upload(UploadVideoValidation $request, Video $video)
    {

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $file_name = $file->getClientOriginalName();
            $path = $video->is_single_video ?
                $path = 'unique_videos/' . $video->id :
                $path = 'courses/' . $video->course_id . '/' . $video->id;

            //has uploaded the video before?
            if (Storage::disk('videos')->exists($path)) {
                Storage::disk('videos')->deleteDirectory($path);
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
        return response(['message' => 'success', 'data' => $video]);
    }

    public function getUnUploadedVideos()
    {
        if (!request()->has('search')) {
            $un_uploaded_videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->whereNull('video_url_name')
                ->select('id', 'courseSection_id', 'course_id', 'fa_title')
                ->paginate(30);
        } else {
            $un_uploaded_videos = Video::query()
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->whereNull('video_url_name')
                ->select('id', 'courseSection_id', 'course_id', 'fa_title')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');

                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');

                    $q->orWhereHas('course', function ($course) {
                        $course->where('fa_title', 'like', '%' . request()->search . '%');
                    });

                    $q->orWhereHas('section', function ($section) {
                        $section->where('fa_title', 'like', '%' . request()->search . '%');
                    });

                })->get();
        }

        return $un_uploaded_videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $un_uploaded_videos]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Video $video
     * @return \Illuminate\Http\JsonResponse
     */

    public function update(StoreVideoValidation $request, Video $video)
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
        $result = $video->delete();
        return response($this->empty_success_message);
    }


    public function restore(DeleteVideoValidation $deleteVideoValidation)
    {
        $result = Video::onlyTrashed()->whereIn('id', request()->ids)
            ->restore();

        return response($this->empty_success_message);
    }

    public function forceDelete(DeleteVideoValidation $deleteVideoValidation)
    {
        $video_ids = request()->ids;

        //single_videos
        $this->ForceDeleteVideosThatDontHaveCourse($video_ids);

        //course_videos
        $this->ForceDeleteVideosThatHaveCourse($video_ids);

        return response($this->empty_success_message);
    }

    public function ForceDeleteVideosThatDontHaveCourse($video_ids)
    {
        $videos = Video::onlyTrashed()->select('id', 'fa_title', 'course_id')
            ->whereIn('id', $video_ids)->doesntHave('course');


        $ids = $videos->pluck('id')->toArray();

        $videos->forceDelete();


        $this->deleteVideosFilesThatDontHaveCourse($ids);

    }

    public function deleteVideosFilesThatDontHaveCourse($ids)
    {
        foreach ($ids as $id) {

            $path = 'unique_videos/' . $id;

            $base_path = Storage::disk('videos');

            if ($base_path->exists($path)) {
                $base_path->deleteDirectory($path);
            }
        }
    }

    public function ForceDeleteVideosThatHaveCourse($video_ids)
    {
        $videos = Video::onlyTrashed()->select('id', 'course_id', 'fa_title')
            ->whereIn('id', $video_ids)->with('course:id,fa_title')
            ->Has('course');

        $video_ids_with_course_ids = [];

        foreach ($videos->get() as $item) {

            $value = ['course_id' => $item->course->id, 'video_id' => $item->id];

            array_push($video_ids_with_course_ids, $value);

        }
        $videos->forceDelete();

        $this->deleteVideosFilesThatHaveCourse($video_ids_with_course_ids);
    }

    public function deleteVideosFilesThatHaveCourse($video_ids_with_course_ids)
    {
        foreach ($video_ids_with_course_ids as $item) {

            $path = 'courses/' . $item['course_id'] . '/' . $item['video_id'];

            $base_path = Storage::disk('videos');

            if ($base_path->exists($path)) {

                $base_path->deleteDirectory($path);
            }
        }
    }

    public function getTrashed()
    {
        if (!request()->has('search')) {
            $trashed_videos = Video::onlyTrashed()->select(
                'id',
                'course_id',
                'courseSection_id',
                'fa_title',
                'video_url_name'
            )->with(['course:id,fa_title', 'section:id,fa_title'])->paginate(30);
        } else {
            $trashed_videos = Video::onlyTrashed()->select(
                'id',
                'course_id',
                'courseSection_id',
                'fa_title',
                'video_url_name'
            )->where('fa_title', 'like', '%' . request()->search . '%')
                ->with(['course:id,fa_title', 'section:id,fa_title'])
                ->OrWhereHas('course', function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                })->orWhereHas('section', function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                })->get();
        }


        return $trashed_videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $trashed_videos]) :
            response($this->empty_success_message);
    }


    public function getSingleVideos()
    {

        if (!request()->has('search')){
            $single_videos = Video::query()
                ->where('is_single_video', 1)
                ->select(
                    'id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )->paginate(30);
        }else{
            $single_videos = Video::query()
                ->where('is_single_video', 1)
                ->where(function ($q){
                    $q->where('fa_title','like','%'.request()->search.'%');
                })
                ->select(
                    'id',
                    'fa_title',
                    'status',
                    'video_url_name'
                )->get();
        }

        return $single_videos->isNotEmpty() ?
            response(['message' => 'success', 'data' => $single_videos]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function deleteMultiple(DeleteVideoValidation $deleteVideoValidation)
    {
        Video::query()->whereIn('id', request()->ids)
            ->delete();

        return response($this->empty_success_message);
    }


}
