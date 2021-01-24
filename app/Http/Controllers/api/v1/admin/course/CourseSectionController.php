<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\courseSections\StoreCourseSectionValidation;
use App\Http\Requests\courses\courseSections\UpdateCourseSectionValidation;
use App\models\Course;
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
        $courses = Course::select('id', 'fa_title')->with('sections:id,course_id,fa_title,status')
            ->get();
        return $courses->isNotEmpty() ?
            response(['message' => 'success', 'data' => $courses]) :
            response($this->empty_success);
    }

    public function getActiveCourseSection()
    {
        $actives_course_sections = CourseSection::whereStatus(1)->select('id', 'fa_title', 'course_id')
            ->with('course:id,fa_title')->get();

        return $actives_course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $actives_course_sections]) :
            response($this->empty_success);

    }

    public function getDeActiveCourseSection()
    {
        $actives_course_sections = CourseSection::whereStatus(0)->select('id', 'fa_title', 'course_id')
            ->with('course:id,fa_title')->get();

        return $actives_course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $actives_course_sections]) :
            response($this->empty_success);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function store(StoreCourseSectionValidation $request)
    {
        $data = $request->only(
            [
                'fa_title',
                'en_title',
                'course_id',
            ]
        );
        $data['status'] = $request->status ? 1 : 0;
        $courseSection = CourseSection::create($data);
        return $courseSection ?
            response($this->empty_success, 201) :
            response($this->failed, 500);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseSection $courseSection)
    {
        return response(['message' => 'success', 'data' => $courseSection]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\CourseSection $courseSection
     * @return \Illuminate\Http\Response
     */


    public function update(UpdateCourseSectionValidation $request, CourseSection $courseSection)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'course_id',
        ]);
        $data['status'] = $request->status ? 1 : 0;
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
        $course_sections = CourseSection::onlyTrashed()->with('course:id,fa_title')->select('id', 'fa_title', 'en_title', 'course_id')
            ->get();
        return $course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_sections]) :
            response($this->empty_success);
    }

    //TODO VALIDATION OF THE RESTORING COURSE SECTION
    public function restore()
    {
        $ids = request()->ids;
        $result = CourseSection::onlyTrashed()->whereIn('id', $ids)->restore();
        return response($this->empty_success);
    }

    //TODO VALIDATION OF THE COURSE SECTION FORCE DELETE
    public function forceDelete()
    {
        $courses = CourseSection::onlyTrashed()->whereIn('id', request()->ids)
            ->forceDelete();
        return response($this->empty_success);
    }

    //TODO VALIDATION OF THE DELETE MULTIPLE COURSE SECTION
    public function deleteMulti()
    {
        $ids = request()->ids;
        $course_sections = CourseSection::whereIn('id', request()->ids)
            ->delete();
        return response($this->empty_success);

    }
}
