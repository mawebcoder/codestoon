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
        //TODO GET ALL COURSE SECTION IN PAGINATION MODE
    }

    public function getActiveCourseSection()
    {
        //TODO GET ACTIVE COURSE SECTION
    }

    public function getDeActiveCourseSection()
    {
        //TODO GET DE ACTIVE COURSE SECTION
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    //TODO VALIDATION OF THE STORE COURSE SECTION
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

    //TODO VALIDATION OF THE UPDATE COURSE SECTION
    public function update(Request $request, CourseSection $courseSection)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'course_id'
        ]);
        $result = $courseSection->update($data);
        return $request ?
            response($this->empty_success) :
            response($this->failed);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */
    public function destroy(CourseSection $courseSection)
    {
        $result = $courseSection->delete();
        return $result ?
            response($this->empty_success) :
            response($this->failed, 50);
    }

    public function getTrashed()
    {
        //TODO GET ALL TRASHED COURSE SECTIONS
    }

    //TODO VALIDATION OF THE RESTORING COURSE SECTION
    public function restore()
    {
        //TODO RESTORING COURSE SECTION
    }

    //TODO VALIDATION OF THE COURSE SECTION FORCE DELETE
    public function forceDelete()
    {
        //TODO FORCE DELETE OF THE COURSE SECTION
    }

    //TODO VALIDATION OF THE DELETE MULTIPLE COURSE SECTION
    public function deleteMultiple()
    {
        //TODO  DELETE MULTIPLE COURSE SECTION
    }
}
