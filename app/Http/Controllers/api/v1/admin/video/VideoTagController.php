<?php

namespace App\Http\Controllers\api\v1\admin\video;

use App\Http\Controllers\Controller;
use App\Http\Requests\video\tag\DeleteVideoTagValidation;
use App\Http\Requests\video\tag\StoreVideoTagValidation;
use App\Http\Requests\video\tag\UpdateVideoTagValidation;
use App\models\VideoTag;
use Illuminate\Http\Request;

class VideoTagController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $failed = ['message' => 'failed', 'data' => null];

    public function __construct()
    {
        //TODO SET THE PERMISSIONS
    }

    public function getActiveVideoTags()
    {
        if (!request()->has('search')) {
            $video_tags = VideoTag::query()->whereStatus(1)->paginate(30);

        } else {
            $video_tags = VideoTag::query()->whereStatus(1)
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->get();
        }
        return $video_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $video_tags]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getDeActiveVideoTags()
    {
        if (!request()->has('search')) {
            $video_tags = VideoTag::query()->whereStatus(0)->paginate(30);

        } else {
            $video_tags = VideoTag::query()->whereStatus(0)
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->get();

        }
        return $video_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $video_tags]) :
            response(['message' => 'success', 'data' => null], 204);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->has('search')) {
            if (!request()->has('select_box')){
                $video_tags = VideoTag::query()->paginate(30);

            }else{
                $video_tags = VideoTag::query()->get();
            }


        } else {
            $video_tags = VideoTag::query()->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhere('en_title', 'like', '%' . request()->search . '%')
                ->get();
        }
        return $video_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $video_tags]) :
            response(['message' => 'success', 'data' => null], 204);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVideoTagValidation $request)
    {
        $data = $request->only(
            [
                'en_title',
                'fa_title',
                'status'
            ]
        );
        $video_tag = VideoTag::create($data);

        return $video_tag ?
            response($this->empty_success_message, 201) :
            response($this->failed);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\VideoTag $videoTag
     * @return \Illuminate\Http\Response
     */


    public function update(UpdateVideoTagValidation $request, VideoTag $videoTag)
    {
        $data = $request->only([
            'en_title',
            'fa_title',
            'status'
        ]);

        $result = $videoTag->update($data);

        return $request ?
            response($this->empty_success_message) :
            response($this->failed);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\VideoTag $videoTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(VideoTag $videoTag)
    {
        $result = $videoTag->delete();
        return $result ?
            response($this->empty_success_message) :
            response($this->failed);
    }


    public function deleteMultiple(DeleteVideoTagValidation $deleteVideoTagValidation)
    {
        $ids = request()->ids;

        $result = VideoTag::whereIn('id', $ids)->delete();

        return response($this->empty_success_message);
    }
}
