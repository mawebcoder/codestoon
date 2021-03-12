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
        if (!request()->has('search')) {
            if (!request()->has('select_box')) {
                $courses = CourseTag::select('fa_title', 'en_title', 'id', 'status')
                    ->paginate(30);
            } else {
                $courses = CourseTag::select('fa_title','id')
                   ->whereStatus(1)->get();
            }

        } else {
            $courses = CourseTag::select('fa_title', 'en_title', 'id', 'status')
                ->where('fa_title', 'like', '%' . request()->search . '%')
                ->orWhere('en_title', 'like', '%' . request()->search . '%')
                ->get();
        }


        return $courses->isNotEmpty()
            ? response([
                'message' => 'success',
                'data' => $courses
            ]) : response([
                'message' => 'success',
                'data' => $courses
            ], 204);
    }

    public function getActivesCourseTags()
    {
        if (!request()->has('search')) {
            $active_course_tags = CourseTag::whereStatus(1)
                ->paginate(30);
        } else {
            $active_course_tags = CourseTag::whereStatus(1)
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->get();
        }
        return $active_course_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $active_course_tags]) :
            response(['message' => 'success', 'data' => null], 204);

    }

    public function getDeActiveCourseTags()
    {
        if (!request()->has('search')) {
            $active_course_tags = CourseTag::whereStatus(0)
                ->paginate(30);
        } else {
            $active_course_tags = CourseTag::whereStatus(0)
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhere('en_title', 'like', '%' . request()->search . '%');
                })->get();
        }
        return $active_course_tags->isNotEmpty() ?
            response(['message' => 'success', 'data' => $active_course_tags]) :
            response(['message' => 'success', 'data' => null], 204);
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
