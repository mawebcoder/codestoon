<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\models\CourseSection;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    public $empty_success = ['message' => 'success', 'data' => null];
    public $failed = ['message' => 'failed', 'data' => null];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $data = $request->only(
            [
                'fa_title',
                'en_title',
                'course_id'
            ]
        );
        $courseSection = CourseSection::create($data);
        return $courseSection ?
            response($this->empty_success, 201) :
            response($this->failed, 500);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */
    public function show(CourseSection $courseSection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseSection $courseSection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseSection $courseSection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseSection $courseSection)
    {
        //
    }
}
