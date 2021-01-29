<?php

namespace App\Http\Controllers\api\v1\admin\course;

use App\Http\Controllers\Controller;
use App\Http\Requests\courses\DeleteMultipleCourseValidation;
use App\Http\Requests\courses\StoreCourseValidation;
use App\Http\Requests\courses\UpdateCourseValidation;
use App\models\Course;
use App\models\CourseCategory;
use App\models\CourseTag;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public $empty_success = ['message' => 'success', 'data' => null];
    public $failed = ['message' => 'failed', 'data' => null];

    public function __construct()
    {
        //TODO SET PERMISSIONS
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $course = Course::select('id', 'fa_title', 'course_image_cover', 'is_active', 'courseCategory_id', 'price', 'user_id')
            ->with(['courseCategory:id,fa_title', 'teacher:id,name,family'])->get();

        return $course->isNotEmpty() ?
            response(['message' => 'success', 'data' => $course]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getActiveCourses()
    {
        $courses = Course::where('is_active', 1)->select('courseCategory_id', 'user_id', 'id', 'course_image_cover', 'fa_title')
            ->with(['courseCategory:id,fa_title', 'teacher:id,name,family'])->get();
        return $courses->isNotEmpty() ?
            response(['message' => 'success', 'data' => $courses]) :
            response(['message' => 'success', 'data' => null], 204);
    }

    public function getDeActiveCourses()
    {
        $courses = Course::where('is_active', 0)->select('courseCategory_id', 'user_id', 'id', 'course_image_cover', 'fa_title')
            ->with(['courseCategory:id,fa_title', 'teacher:id,name,family'])->get();
        return $courses->isNotEmpty() ?
            response(['message' => 'success', 'data' => $courses]) :
            response(['message' => 'success', 'data' => null], 204);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCourseValidation $request)
    {
        $data = $request->only([
            'fa_title',
            'en_title',
            'description',
            'price',
            'has_discount',
            'level',
            'user_id',
            'description',
            'short_description',
            'meta',
            'courseCategory_id'
        ]);
        $data['has_discount'] = intval($request->discount_value) ? 1 : 0;
        $data['discount_value'] = intval($request->discount_value) ?? 0;
        $data['is_active'] = $request->is_active ? 1 : 0;
        $data['is_special_subscription'] = $request->is_special_subscription ? 1 : 0;
        $data['is_completed_course'] = $request->is_completed_course ? 1 : 0;
        $course = Course::create($data);

        $this->syncCategories($course, $request);

        $this->syncTags($course, $request);

        $this->upload($course, $request);

        return $course ?
            response($this->empty_success, 201) :
            response($this->failed, 500);

    }

    public function syncCategories($course, $request)
    {
        $get_all_categories_parent = CourseCategory::getAllParentsIds($request->courseCategory_id);
        $course->courseCategories()->sync($get_all_categories_parent);
    }

    public function syncTags($course, $request)
    {
        if ($request->has('tag_ids')) {
            $course->tags()->sync($request->tag_ids);
        }
    }

    public function upload($course, $request)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = 'images/courses/covers/' . $course->id;
            $file_name = $file->getClientOriginalName();
            if (Storage::disk('public')->exists('images/courses/covers/' . $course->id)) {
                Storage::disk('public')->deleteDirectory('images/courses/covers/' . $course->id);
            }
            $request->file('file')->storeAs($path, $file_name, 'public');
            $course->update([
                'course_image_cover' => $file_name
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function edit(Course $course)
    {
        $course_categories = CourseCategory::select('id', 'fa_title')->get();
        $course_tags = CourseTag::select('id', 'fa_title')->get();
        return response(['message' => 'success', 'data' => [
            'course' => $course,
            'categories' => $course_categories,
            'course_tags' => $course_tags
        ]]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */


    public function update(UpdateCourseValidation $request, Course $course)
    {
        $inputs = $request->only([
            'fa_title',
            'en_title',
            'description',
            'price',
            'has_discount',
            'level',
            'user_id',
            'description',
            'short_description',
            'meta',
            'courseCategory_id'
        ]);
        $inputs['has_discount'] = intval($request->discount_value) ? 1 : 0;
        $inputs['discount_value'] = intval($request->discount_value) ?? 0;
        $inputs['is_active'] = $request->is_active ? 1 : 0;
        $inputs['is_special_subscription'] = $request->is_special_subscription ? 1 : 0;
        $inputs['is_completed_course'] = $request->is_completed_course ? 1 : 0;
        $result = $course->update($inputs);

        $this->syncCategories($course, $request);

        $this->syncTags($course, $request);

        $this->upload($course, $request);

        return $result ?
            response($this->empty_success, 200) :
            response($this->failed, 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\models\Course $course
     * @return \Illuminate\Http\Response
     */
    public function destroy(Course $course)
    {
        $result = $course->delete();
        if ($result) {
            if (file_exists(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover))) {
                unlink(storage_path('app/public/images/courses/covers/' . $course->id . '/' . $course->course_image_cover));
            };
            return response($this->empty_success);
        }
        return response($this->failed, 500);

    }


    public function forceDelete(DeleteMultipleCourseValidation $deleteMultipleCourseValidation)
    {
        $courses = Course::onlyTrashed()->whereIn('id', $deleteMultipleCourseValidation->ids)
            ->forceDelete();
        foreach ($deleteMultipleCourseValidation->ids as $id) {
            if (is_dir(storage_path('app/public/images/courses/covers/' . $id))) {
                Storage::disk('public')->deleteDirectory('images/courses/covers/' . $id);
            }
        }

        return response($this->empty_success);
    }


    public function restore(DeleteMultipleCourseValidation $deleteMultipleCourseValidation)
    {
        $courses = Course::onlyTrashed()->whereIn('id', $deleteMultipleCourseValidation->ids)
            ->restore();
        return $courses ?
            response($this->empty_success) :
            response($this->failed);
    }

    public function getTrashed()
    {
        $courses = Course::onlyTrashed()->select('id', 'fa_title', 'en_title')->get();
        return $courses->isNotEmpty() ?
            response(['message' => 'success', 'data' => $courses]) :
            response(['message' => 'success', 'data' => null], 204);
    }


    public function deleteMultiple(DeleteMultipleCourseValidation $deleteMultipleCourseValidation)
    {
        $result = Course::whereIn('id', request()->ids)->delete();
        return $result ?
            response($this->empty_success) :
            response($this->failed);
    }

}
