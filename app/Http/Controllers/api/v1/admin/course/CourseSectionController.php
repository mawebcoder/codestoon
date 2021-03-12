<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\courseSections\DeleteCourseSectionValidation;
use App\Http\Requests\courses\courseSections\StoreCourseSectionValidation;
use App\Http\Requests\courses\courseSections\UpdateCourseSectionValidation;
use App\models\Course;
use App\models\CourseSection;
use Illuminate\Http\Request;

class CourseSectionController extends Controller
{
    public $empty_success = ['message' => 'success', 'data' => null];
    public $failed = ['message' => 'failed', 'data' => null];

    public function __construct()
    {
        //TODO SET PERMISSIONS OF THE COURSE CATEGORIES
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!request()->has('search')) {
            if (!request()->has('select_box')) {
                $course_sections = CourseSection::query()->select('id', 'status', 'fa_title', 'course_id', 'meta')
                    ->with('course:id,fa_title')->paginate(30);
            } else {
                $course_sections = CourseSection::query()->select('id', 'fa_title')
                    ->whereStatus(1)->get();
            }

        } else {
            $course_sections = CourseSection::query()
                ->select('id', 'status', 'fa_title', 'course_id', 'meta')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                })
                ->orWhereHas('course', function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                })
                ->with('course:id,fa_title')->get();
        }


        return $course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_sections]) :
            response($this->empty_success, 204);
    }


    public function getCourseSections(Course $course)
    {
        $sections = $course->sections()->select('id', 'fa_title')->get();
        return $sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $sections]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getActiveCourseSection()
    {


        if (!request()->has('search')) {
            $course_sections = CourseSection::query()->whereStatus(1)->select('id', 'status', 'fa_title', 'course_id', 'meta')
                ->with('course:id,fa_title')->paginate(30);
        } else {
            $course_sections = CourseSection::query()
                ->select('id', 'status', 'fa_title', 'course_id', 'meta')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhereHas('course', function ($row) {
                        $row->where('fa_title', 'like', '%' . request()->search . '%');

                    });
                })->Where('status', 1)
                ->with('course:id,fa_title')->get();
        }

        return $course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_sections]) :
            response($this->empty_success, 204);

    }

    public function getDeActiveCourseSection()
    {

        if (!request()->has('search')) {
            $course_sections = CourseSection::query()->whereStatus(0)->select('id', 'status', 'fa_title', 'course_id', 'meta')
                ->with('course:id,fa_title')->paginate(30);
        } else {
            $course_sections = CourseSection::query()
                ->select('id', 'status', 'fa_title', 'course_id', 'meta')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhereHas('course', function ($row) {
                        $row->where('fa_title', 'like', '%' . request()->search . '%');

                    });
                })->Where('status', 0)
                ->with('course:id,fa_title')->get();
        }

        return $course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_sections]) :
            response($this->empty_success, 204);
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
                'meta',
                'description'
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
            'description',
            'meta'
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
        if (!request()->has('search')) {
            $course_sections = CourseSection::onlyTrashed()
                ->with('course:id,fa_title')
                ->select('id', 'fa_title', 'course_id')
                ->paginate(30);
        } else {
            $course_sections = CourseSection::onlyTrashed()
                ->with('course:id,fa_title')->select('id', 'fa_title', 'course_id')
                ->where(function ($q) {
                    $q->where('fa_title', 'like', '%' . request()->search . '%');
                    $q->orWhereHas('course', function ($row) {
                        $row->where('fa_title', 'like', '%' . request()->search . '%');
                    });
                })->get();

        }
        return $course_sections->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course_sections]) :
            response($this->empty_success);
    }


    public function restore(DeleteCourseSectionValidation $deleteCourseSectionValidation)
    {
        $ids = $deleteCourseSectionValidation->ids;
        $result = CourseSection::onlyTrashed()->whereIn('id', $ids)->restore();
        return response($this->empty_success);
    }


    public function forceDelete(DeleteCourseSectionValidation $deleteCourseSectionValidation)
    {
        $courses = CourseSection::onlyTrashed()->whereIn('id', $deleteCourseSectionValidation->ids)
            ->forceDelete();
        return response($this->empty_success);
    }


    public function deleteMulti(DeleteCourseSectionValidation $deleteCourseSectionValidation)
    {
        $ids = $deleteCourseSectionValidation->ids;
        $course_sections = CourseSection::whereIn('id', $ids)
            ->delete();
        return response($this->empty_success);

    }
}
