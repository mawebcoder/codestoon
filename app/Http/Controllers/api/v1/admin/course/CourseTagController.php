<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\tag\DeleteCourseTagValidation;
use App\Http\Requests\courses\tag\StoreCourseTagValidation;
use App\Http\Requests\courses\tag\UpdateCourseTagValidation;
use App\models\CourseTag;

class CourseTagController extends Controller
{
    public $empty_success_message = ['message' => 'success', 'data' => null];
    public $failed_message = ['message' => 'failed', 'data' => null];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //TODO SET PERMISSIONS FOR THESE
    }
    public function index()
    {
        $courses = CourseTag::select('fa_title', 'en_title', 'id')
            ->get();
        return response([
            'message' => 'success',
            'data' => $courses
        ]);
    }

    public function getActiveCourseTags()
    {
        //TODO GET ACTIVE COURSE TAGS
    }
    public function getDeActiveCourseTags()
    {
        //TODO GET DE ACTIVE COURSE TAGS
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseTagValidation $request)
    {
        $data = [
            'fa_title' => $request->fa_title,
            'en_title' => $request->en_title ?? null,
            'status' => $request->status ? 1 : 0
        ];
        $result = CourseTag::create($data);
        return $result ?
            response($this->empty_success_message, 201) :
            response($this->failed_message, 500);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\CourseTag $courseTag
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseTag $courseTag)
    {
        return response(['message' => 'success', 'data' => $courseTag]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\CourseTag $courseTag
     * @return \Illuminate\Http\Response
     */

    public function update(UpdateCourseTagValidation $request, CourseTag $courseTag)
    {
        $data = [
            'fa_title' => $request->fa_title,
            'en_title' => $request->en_title ?? null,
            'status' => $request->status ? 1 : 0
        ];
        $result = $courseTag->update($data);
        return $request ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\CourseTag $courseTag
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseTag $courseTag)
    {
        $result = $courseTag->delete();

        return $result ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }


    public function deleteMultiple(DeleteCourseTagValidation $deleteCourseTagValidation)
    {
        $result = CourseTag::whereIn('id', request()->ids)->delete();
        return $result ?
            response($this->empty_success_message) :
            response($this->failed_message);
    }

}
