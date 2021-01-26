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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $video_tags = VideoTag::paginate(30);
        return $video_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $video_tags]) :
            response(['message' => 'success', 'data' => null],204);
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
     * Show the form for editing the specified resource.
     *
     * @param \App\models\VideoTag $videoTag
     * @return \Illuminate\Http\Response
     */
    public function edit(VideoTag $videoTag)
    {
        return  response(['message'=>'success','data'=>$videoTag]);
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
        $ids=request()->ids;

        $result=VideoTag::whereIn('id',$ids)->delete();

        return response($this->empty_success_message);
    }
}
